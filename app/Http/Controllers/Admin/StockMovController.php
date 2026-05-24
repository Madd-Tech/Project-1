<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockMove;
use App\Models\Order;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StockMovController extends Controller
{
    /**
     * Build the base query for stock movements with shared filters.
     */
    private function buildQuery(Request $request)
    {
        $query = StockMove::with('product');

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        switch ($request->input('sort_by', 'newest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'a-z':
                $query->orderBy(
                    Product::select('name')
                        ->whereColumn('products.id', 'stock_moves.product_id')
                        ->limit(1),
                    'asc'
                );
                break;
            case 'z-a':
                $query->orderBy(
                    Product::select('name')
                        ->whereColumn('products.id', 'stock_moves.product_id')
                        ->limit(1),
                    'desc'
                );
                break;
            case 'most_total':
                // Will be handled after enrichment (post-query sort)
                $query->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }

    /**
     * Enrich a collection of stock moves with order total amounts.
     */
    private function enrichWithOrderTotals($movements)
    {
        $orderNumbers = $movements
            ->pluck('reference')
            ->filter()
            ->map(function ($reference) {
                if (preg_match('/(ORD-\d{8}-\d{4})/', (string) $reference, $matches)) {
                    return $matches[1];
                }
                return null;
            })
            ->filter()
            ->unique()
            ->values();

        $orderTotals = Order::whereIn('order_number', $orderNumbers)
            ->pluck('total_amount', 'order_number');

        return $movements->map(function ($move) use ($orderTotals) {
            $orderNumber = null;
            if (preg_match('/(ORD-\d{8}-\d{4})/', (string) $move->reference, $matches)) {
                $orderNumber = $matches[1];
            }
            $move->order_number = $orderNumber;
            $move->order_total_amount = $orderNumber ? ($orderTotals[$orderNumber] ?? null) : null;
            return $move;
        });
    }

    public function index(Request $request)
    {
        $query = $this->buildQuery($request);

        $movements = $query->paginate(10)->withQueryString();

        $movements->setCollection(
            $this->enrichWithOrderTotals($movements->getCollection())
        );

        // Stats
        $stats = [
            'total'  => StockMove::count(),
            'masuk'  => StockMove::where('type', 'produk masuk')->count(),
            'keluar' => StockMove::where('type', 'produk keluar')->count(),
        ];

        return Inertia::render('Admin/StockMov', [
            'movements' => $movements,
            'stats' => $stats,
            'filters' => $request->only(['sort_by', 'date_from', 'date_to', 'type']),
            'admin' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ]);
    }

    /**
     * Export stock movements to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = $this->buildQuery($request);

        // Get all records (no pagination for PDF)
        $movements = $query->get();

        // Enrich with order totals
        $movements = $this->enrichWithOrderTotals($movements);

        // If sort_by is 'most_total', sort by order total amount descending
        $sortBy = $request->input('sort_by', 'newest');
        if ($sortBy === 'most_total') {
            $movements = $movements->sortByDesc(function ($move) {
                return $move->order_total_amount ?? 0;
            })->values();
        }

        // Stats for the filtered set
        $stats = [
            'total'  => $movements->count(),
            'masuk'  => $movements->where('type', 'produk masuk')->count(),
            'keluar' => $movements->where('type', 'produk keluar')->count(),
        ];

        $pdf = Pdf::loadView('Admin.stockmov-pdf', [
            'movements' => $movements,
            'stats'     => $stats,
            'sortBy'    => $sortBy,
            'type'      => $request->input('type', ''),
            'dateFrom'  => $request->input('date_from', ''),
            'dateTo'    => $request->input('date_to', ''),
        ]);

        $pdf->setPaper('A4', 'landscape');

        $filename = 'riwayat-stok-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->stream($filename);
    }
}
