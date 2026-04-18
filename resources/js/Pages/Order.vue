<template>
  <div class="min-h-screen bg-dark-900">

    <div class="glass border-b border-white/5">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 group">
          <div class="w-10 h-10 bg-gradient-to-br from-electric to-neon rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <span class="text-xl font-bold font-[Outfit] tracking-tight">
            <span class="text-white">Babuihan</span><span class="text-electric">Store</span>
          </span>
        </a>
        <div class="flex items-center gap-2 text-[#25D366] text-sm">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
          <span>Order via WhatsApp</span>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="/" class="hover:text-electric transition-colors">Home</a>
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        <a href="/products" class="hover:text-electric transition-colors">Products</a>
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        <span class="text-gray-300">Order</span>
      </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

          <!-- Invoice Header -->
          <div class="glass-card rounded-3xl p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-electric/5 rounded-full blur-[80px]"></div>
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
              <div>
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-12 h-12 bg-gradient-to-br from-electric to-neon rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                  </div>
                  <div>
                    <h2 class="text-xl font-bold text-white font-[Outfit]">BabuihanStore</h2>
                    <p class="text-gray-500 text-sm">Premium Footwear Store</p>
                  </div>
                </div>
                <div class="text-sm text-gray-400 space-y-0.5 mt-4">
                  <p>Jl. Raya Babuihan No. 88</p>
                  <p>Malang, Jawa Timur 65115</p>
                  <p>Indonesia</p>
                </div>
              </div>

              <div class="bg-[#25D366]/5 border border-[#25D366]/10 rounded-2xl p-5 min-w-[260px]">
                <div class="flex items-center justify-between mb-3">
                  <span class="text-xs font-semibold tracking-widest uppercase text-[#25D366]">New Order</span>
                  <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-[#25D366]/20 text-[#25D366] uppercase tracking-wider">WhatsApp</span>
                </div>
                <div class="space-y-2 text-sm">
                  <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="text-gray-300">{{ currentDate }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-500">Stok tersedia</span>
                    <span class="text-gray-300">{{ product.stock }} pcs</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Product detail -->
          <div class="glass-card rounded-3xl p-8">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-9 h-9 rounded-xl bg-electric/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-white font-[Outfit]">Detail Pesanan</h3>
            </div>

            <div class="hidden sm:grid grid-cols-12 gap-4 text-xs text-gray-500 uppercase tracking-wider font-semibold pb-3 border-b border-white/5 mb-4">
              <div class="col-span-5">Produk</div>
              <div class="col-span-2 text-center">Qty</div>
              <div class="col-span-2 text-right">Harga</div>
              <div class="col-span-3 text-right">Total</div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center py-4 border-b border-white/5">
              <div class="sm:col-span-5 flex items-center gap-4">
                <div class="w-16 h-16 bg-dark-700 rounded-xl overflow-hidden flex-shrink-0">
                  <img
                    :src="product.image ? '/storage/' + product.image : '/images/placeholder.png'"
                    :alt="product.name"
                    class="w-full h-full object-contain p-1"
                  />
                </div>
                <div>
                  <p class="text-white font-medium text-sm">{{ product.name }}</p>
                  <p class="text-gray-500 text-xs">{{ product.category ? product.category.name : 'Uncategorized' }}</p>
                </div>
              </div>
              <div class="sm:col-span-2 text-center text-gray-300 text-sm">
                <span class="sm:hidden text-gray-500">Qty: </span>{{ quantity }}
              </div>
              <div class="sm:col-span-2 text-right text-gray-300 text-sm">
                <span class="sm:hidden text-gray-500">Harga: </span>Rp {{ formatPrice(product.price) }}
              </div>
              <div class="sm:col-span-3 text-right text-white font-semibold text-sm">
                <span class="sm:hidden text-gray-500">Total: </span>Rp {{ formatPrice(subtotal) }}
              </div>
            </div>

            <!-- Quantity selector -->
            <div class="mt-6">
              <label class="block text-sm text-gray-400 mb-3">Jumlah</label>
              <div class="flex items-center gap-4">
                <button
                  type="button"
                  @click="decrementQty"
                  class="w-11 h-11 flex items-center justify-center bg-dark-800 border border-white/10 rounded-xl text-white hover:bg-white/5 transition-all"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                </button>
                <span class="w-12 text-center text-white text-lg font-bold">{{ quantity }}</span>
                <button
                  type="button"
                  @click="incrementQty"
                  class="w-11 h-11 flex items-center justify-center bg-dark-800 border border-white/10 rounded-xl text-white hover:bg-white/5 transition-all"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </button>
                <span class="text-xs text-gray-500">Stok: {{ product.stock }} pcs</span>
              </div>
            </div>

            <div class="mt-4 p-4 bg-dark-800/50 rounded-xl">
              <p class="text-xs text-gray-500 mb-1">Deskripsi</p>
              <p class="text-sm text-gray-400">{{ product.description || 'Tidak ada deskripsi produk.' }}</p>
            </div>
          </div>

          <!-- WhatsApp message preview -->
          <div class="glass-card rounded-3xl p-8">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-9 h-9 rounded-xl bg-[#25D366]/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-white font-[Outfit]">Pesan WhatsApp</h3>
            </div>
            <div class="bg-dark-800/60 border border-white/5 rounded-2xl p-5">
              <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Preview pesan yang akan dikirim:</p>
              <div class="bg-[#25D366]/5 border border-[#25D366]/10 rounded-xl p-4">
                <p class="text-gray-200 text-sm leading-relaxed font-mono break-words">{{ whatsappMessage }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="lg:col-span-1">
          <div class="sticky top-6 space-y-6">
            <div class="glass-card rounded-3xl p-7">
              <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white font-[Outfit]">Ringkasan</h3>
                <span class="px-2.5 py-1 text-[10px] uppercase font-bold tracking-wider rounded-full bg-electric/10 text-electric">IDR</span>
              </div>

              <div class="text-center mb-6 py-5 bg-gradient-to-br from-electric/5 to-neon/5 rounded-2xl border border-white/5">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Total Estimasi</p>
                <p class="text-3xl font-bold text-white font-[Outfit]">Rp {{ formatPrice(totalAmount) }}</p>
                <p class="text-xs text-gray-500 mt-1">(termasuk PPN 11%)</p>
              </div>

              <div class="space-y-3 mb-6">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">{{ product.name }} x{{ quantity }}</span>
                  <span class="text-gray-300">Rp {{ formatPrice(subtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">PPN (11%)</span>
                  <span class="text-gray-300">Rp {{ formatPrice(tax) }}</span>
                </div>
                <div class="border-t border-white/5 pt-3 flex justify-between text-sm font-semibold">
                  <span class="text-gray-300">Total</span>
                  <span class="text-white">Rp {{ formatPrice(totalAmount) }}</span>
                </div>
              </div>

              <!-- WhatsApp Order Button -->
              <button
                @click="orderViaWhatsApp"
                class="w-full py-4 rounded-2xl font-semibold text-white transition-all duration-300 flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#20BA5A] hover:shadow-lg hover:shadow-[#25D366]/30 transform hover:scale-[1.02] active:scale-[0.98]"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Pesan via WhatsApp
              </button>
            </div>

            <div class="glass-card rounded-2xl p-5">
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#25D366]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-4 h-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-white text-sm font-medium mb-1">Pesan Mudah & Cepat</p>
                  <p class="text-gray-500 text-xs leading-relaxed">Klik tombol di atas untuk membuka WhatsApp. Pesan Anda sudah otomatis terisi.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
});

const waNumber = import.meta.env.VITE_WHATSAPP_NUMBER || '6281327370987';

const quantity = ref(1);

const currentDate = computed(() => {
  return new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
});

const subtotal = computed(() => props.product.price * quantity.value);
const tax = computed(() => subtotal.value * 0.11);
const totalAmount = computed(() => subtotal.value + tax.value);

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(Math.round(price));
};

const incrementQty = () => {
  if (quantity.value < props.product.stock) {
    quantity.value++;
  }
};

const decrementQty = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
};

// Build WhatsApp message
const whatsappMessage = computed(() => {
  return `Hallo saya ingin pesan ${props.product.name} x${quantity.value}`;
});

const orderViaWhatsApp = () => {
  const encodedMessage = encodeURIComponent(whatsappMessage.value);
  const waUrl = `https://wa.me/${waNumber}?text=${encodedMessage}`;
  window.open(waUrl, '_blank');
};
</script>
