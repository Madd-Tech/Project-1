<template>
  <div class="min-h-screen bg-dark-900">
    <!-- Minimal Top Bar -->
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
        <div class="flex items-center gap-2 text-gray-400 text-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
          <span>Secure Checkout</span>
        </div>
      </div>
    </div>

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="/" class="hover:text-electric transition-colors">Home</a>
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        <span class="text-gray-300">Checkout</span>
      </nav>
    </div>

    <!-- Empty Cart -->
    <div v-if="cartItems.length === 0" class="max-w-lg mx-auto px-4 py-20 text-center">
      <div class="w-24 h-24 mx-auto rounded-3xl bg-dark-700 flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
      </div>
      <h2 class="text-2xl font-bold text-white font-[Outfit] mb-2">Keranjang Kosong</h2>
      <p class="text-gray-400 mb-6">Anda belum menambahkan produk ke keranjang.</p>
      <a href="/products" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-electric to-neon text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-electric/25 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Lihat Produk
      </a>
    </div>

    <!-- Main Content -->
    <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
      <form @submit.prevent="submitOrder">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

          <!-- LEFT COLUMN -->
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
                <div class="bg-electric/5 border border-electric/10 rounded-2xl p-5 min-w-[260px]">
                  <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold tracking-widest uppercase text-electric">Cart Checkout</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-neon/20 text-neon uppercase tracking-wider">{{ cartItems.length }} Items</span>
                  </div>
                  <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                      <span class="text-gray-500">Tanggal</span>
                      <span class="text-gray-300">{{ currentDate }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-500">Jatuh Tempo</span>
                      <span class="text-amber font-medium">{{ dueDate }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Customer Details -->
            <div class="glass-card rounded-3xl p-8">
              <div class="flex items-center gap-3 mb-6">
                <div class="w-9 h-9 rounded-xl bg-neon/10 flex items-center justify-center">
                  <svg class="w-5 h-5 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold text-white font-[Outfit]">Detail Pelanggan</h3>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <label class="block text-sm text-gray-400 mb-2">Nama Lengkap</label>
                  <input v-model="form.customer_name" type="text" placeholder="Ahmad Fadli" class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-electric/50 focus:ring-1 focus:ring-electric/25 transition-all" required />
                  <p v-if="errors.customer_name" class="text-red-400 text-xs mt-1">{{ errors.customer_name }}</p>
                </div>
                <div>
                  <label class="block text-sm text-gray-400 mb-2">Email</label>
                  <input v-model="form.customer_email" type="email" placeholder="ahmad@email.com" class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-electric/50 focus:ring-1 focus:ring-electric/25 transition-all" required />
                  <p v-if="errors.customer_email" class="text-red-400 text-xs mt-1">{{ errors.customer_email }}</p>
                </div>
                <div>
                  <label class="block text-sm text-gray-400 mb-2">No. Telepon</label>
                  <input v-model="form.customer_phone" type="tel" placeholder="08123456789" class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-electric/50 focus:ring-1 focus:ring-electric/25 transition-all" required />
                  <p v-if="errors.customer_phone" class="text-red-400 text-xs mt-1">{{ errors.customer_phone }}</p>
                </div>
                <div>
                  <label class="block text-sm text-gray-400 mb-2">Alamat Pengiriman</label>
                  <input v-model="form.customer_address" type="text" placeholder="Jl. Contoh No. 123, Kota" class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-electric/50 focus:ring-1 focus:ring-electric/25 transition-all" required />
                  <p v-if="errors.customer_address" class="text-red-400 text-xs mt-1">{{ errors.customer_address }}</p>
                </div>
              </div>
            </div>

            <!-- Order Items -->
            <div class="glass-card rounded-3xl p-8">
              <div class="flex items-center gap-3 mb-6">
                <div class="w-9 h-9 rounded-xl bg-electric/10 flex items-center justify-center">
                  <svg class="w-5 h-5 text-electric" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold text-white font-[Outfit]">Detail Pesanan</h3>
                <span class="ml-auto px-2.5 py-1 text-[10px] uppercase font-bold tracking-wider rounded-full bg-electric/10 text-electric">{{ cartItems.length }} produk</span>
              </div>

              <!-- Table Header -->
              <div class="hidden sm:grid grid-cols-12 gap-4 text-xs text-gray-500 uppercase tracking-wider font-semibold pb-3 border-b border-white/5 mb-2">
                <div class="col-span-5">Produk</div>
                <div class="col-span-2 text-center">Qty</div>
                <div class="col-span-2 text-right">Harga</div>
                <div class="col-span-3 text-right">Total</div>
              </div>

              <!-- Product Rows -->
              <div
                v-for="(item, index) in cartItems"
                :key="item.id"
                class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center py-4"
                :class="index < cartItems.length - 1 ? 'border-b border-white/5' : ''"
              >
                <div class="sm:col-span-5 flex items-center gap-4">
                  <div class="w-14 h-14 bg-dark-700 rounded-xl overflow-hidden flex-shrink-0">
                    <img
                      :src="item.image ? '/storage/' + item.image : '/images/placeholder.png'"
                      :alt="item.name"
                      class="w-full h-full object-contain p-1"
                    />
                  </div>
                  <div class="min-w-0">
                    <p class="text-white font-medium text-sm truncate">{{ item.name }}</p>
                    <p class="text-gray-500 text-xs">{{ item.category }}</p>
                  </div>
                </div>
                <div class="sm:col-span-2 flex items-center justify-center gap-1.5">
                  <button type="button" @click="decrementQuantity(item.id)" class="w-7 h-7 flex items-center justify-center bg-dark-700 rounded-lg text-gray-400 hover:text-white hover:bg-dark-600 transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                  </button>
                  <span class="w-8 text-center text-white text-sm font-medium">{{ item.quantity }}</span>
                  <button type="button" @click="incrementQuantity(item.id)" class="w-7 h-7 flex items-center justify-center bg-dark-700 rounded-lg text-gray-400 hover:text-white hover:bg-dark-600 transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  </button>
                </div>
                <div class="sm:col-span-2 text-right text-gray-300 text-sm">
                  <span class="sm:hidden text-gray-500">Harga: </span>Rp {{ formatPrice(item.price) }}
                </div>
                <div class="sm:col-span-3 text-right flex items-center justify-end gap-2">
                  <span class="text-white font-semibold text-sm">Rp {{ formatPrice(item.price * item.quantity) }}</span>
                  <button type="button" @click="removeFromCart(item.id)" class="p-1 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Payment Method -->
            <div class="glass-card rounded-3xl p-8">
              <div class="flex items-center gap-3 mb-6">
                <div class="w-9 h-9 rounded-xl bg-amber/10 flex items-center justify-center">
                  <svg class="w-5 h-5 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                  </svg>
                </div>
                <h3 class="text-lg font-semibold text-white font-[Outfit]">Metode Pembayaran</h3>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                <button type="button" @click="form.payment_method = 'bank_transfer'" :class="['p-4 rounded-2xl border-2 transition-all duration-300 text-left', form.payment_method === 'bank_transfer' ? 'border-electric bg-electric/5 shadow-lg shadow-electric/10' : 'border-white/5 hover:border-white/15 bg-dark-800']">
                  <div class="flex items-center gap-3">
                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center', form.payment_method === 'bank_transfer' ? 'bg-electric/20' : 'bg-white/5']">
                      <svg class="w-5 h-5" :class="form.payment_method === 'bank_transfer' ? 'text-electric' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                      <p :class="['font-semibold text-sm', form.payment_method === 'bank_transfer' ? 'text-white' : 'text-gray-400']">Virtual Account</p>
                      <p class="text-[11px] text-gray-500">Bank Transfer / VA</p>
                    </div>
                  </div>
                </button>
                <button type="button" @click="form.payment_method = 'qris'; form.payment_bank = null" :class="['p-4 rounded-2xl border-2 transition-all duration-300 text-left', form.payment_method === 'qris' ? 'border-neon bg-neon/5 shadow-lg shadow-neon/10' : 'border-white/5 hover:border-white/15 bg-dark-800']">
                  <div class="flex items-center gap-3">
                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center', form.payment_method === 'qris' ? 'bg-neon/20' : 'bg-white/5']">
                      <svg class="w-5 h-5" :class="form.payment_method === 'qris' ? 'text-neon' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <div>
                      <p :class="['font-semibold text-sm', form.payment_method === 'qris' ? 'text-white' : 'text-gray-400']">QRIS</p>
                      <p class="text-[11px] text-gray-500">Scan QR Code</p>
                    </div>
                  </div>
                </button>
                <button type="button" @click="form.payment_method = 'gopay'; form.payment_bank = null" :class="['p-4 rounded-2xl border-2 transition-all duration-300 text-left', form.payment_method === 'gopay' ? 'border-[#00AED6] bg-[#00AED6]/5 shadow-lg shadow-[#00AED6]/10' : 'border-white/5 hover:border-white/15 bg-dark-800']">
                  <div class="flex items-center gap-3">
                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center', form.payment_method === 'gopay' ? 'bg-[#00AED6]/20' : 'bg-white/5']">
                      <svg class="w-5 h-5" :class="form.payment_method === 'gopay' ? 'text-[#00AED6]' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                      </svg>
                    </div>
                    <div>
                      <p :class="['font-semibold text-sm', form.payment_method === 'gopay' ? 'text-white' : 'text-gray-400']">GoPay</p>
                      <p class="text-[11px] text-gray-500">GoPay App</p>
                    </div>
                  </div>
                </button>
              </div>

              <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
                <div v-if="form.payment_method === 'bank_transfer'" class="space-y-3">
                  <p class="text-sm text-gray-400 mb-3">Pilih Bank:</p>
                  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                    <button v-for="bank in banks" :key="bank.code" type="button" @click="form.payment_bank = bank.code" :class="['p-4 rounded-xl border-2 transition-all duration-300 flex flex-col items-center gap-2', form.payment_bank === bank.code ? 'border-electric bg-electric/5' : 'border-white/5 hover:border-white/15 bg-dark-800']">
                      <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg font-bold" :class="form.payment_bank === bank.code ? 'text-electric' : 'text-gray-500'" :style="{ backgroundColor: form.payment_bank === bank.code ? bank.color + '15' : 'rgba(255,255,255,0.03)' }">
                        {{ bank.shortName }}
                      </div>
                      <span :class="['text-xs font-medium', form.payment_bank === bank.code ? 'text-white' : 'text-gray-500']">{{ bank.name }}</span>
                    </button>
                  </div>
                </div>
              </transition>
              <p v-if="errors.payment" class="text-red-400 text-xs mt-2">{{ errors.payment }}</p>
              <p v-if="errors.items" class="text-red-400 text-xs mt-2">{{ errors.items }}</p>
            </div>
          </div>

          <!-- RIGHT COLUMN: Summary -->
          <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
              <div class="glass-card rounded-3xl p-7">
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-lg font-semibold text-white font-[Outfit]">Ringkasan</h3>
                  <span class="px-2.5 py-1 text-[10px] uppercase font-bold tracking-wider rounded-full bg-electric/10 text-electric">IDR</span>
                </div>

                <div class="text-center mb-6 py-5 bg-gradient-to-br from-electric/5 to-neon/5 rounded-2xl border border-white/5">
                  <p class="text-gray-500 text-xs uppercase tracking-wider mb-1">Total Pembayaran</p>
                  <p class="text-3xl font-bold text-white font-[Outfit]">Rp {{ formatPrice(totalAmount) }}</p>
                  <p class="text-xs text-gray-500 mt-1">(termasuk PPN 11%)</p>
                </div>

                <!-- Items summary -->
                <div class="space-y-2 mb-4">
                  <div v-for="item in cartItems" :key="'s-'+item.id" class="flex justify-between text-xs">
                    <span class="text-gray-500 truncate mr-2">{{ item.name }} x{{ item.quantity }}</span>
                    <span class="text-gray-400 flex-shrink-0">Rp {{ formatPrice(item.price * item.quantity) }}</span>
                  </div>
                </div>

                <div class="space-y-3 mb-6 border-t border-white/5 pt-4">
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
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

                <button type="submit" :disabled="isSubmitting" :class="['w-full py-4 rounded-2xl font-semibold text-white transition-all duration-300 flex items-center justify-center gap-2', isSubmitting ? 'bg-gray-700 cursor-not-allowed' : 'bg-gradient-to-r from-electric to-neon hover:shadow-lg hover:shadow-electric/25 transform hover:scale-[1.02] active:scale-[0.98]']">
                  <svg v-if="isSubmitting" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                  {{ isSubmitting ? 'Memproses...' : 'Bayar Sekarang' }}
                </button>
              </div>

              <div class="glass-card rounded-2xl p-5">
                <div class="flex items-start gap-3">
                  <div class="w-8 h-8 rounded-lg bg-neon/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                  </div>
                  <div>
                    <p class="text-white text-sm font-medium mb-1">Pembayaran Aman</p>
                    <p class="text-gray-500 text-xs leading-relaxed">Transaksi diproses melalui Midtrans Payment Gateway dengan enkripsi SSL 256-bit.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useCart } from '../Composables/useCart';

const {
  items: cartItems,
  totalItems,
  subtotal,
  tax,
  totalAmount,
  incrementQuantity,
  decrementQuantity,
  removeFromCart,
  clearCart,
} = useCart();

const page = usePage();
const errors = computed(() => page.props.errors || {});
const isSubmitting = ref(false);

const form = reactive({
  customer_name: '',
  customer_email: '',
  customer_phone: '',
  customer_address: '',
  payment_method: 'bank_transfer',
  payment_bank: 'bca',
});

const banks = [
  { code: 'bca', name: 'BCA', shortName: 'BCA', color: '#003D7A' },
  { code: 'bni', name: 'BNI', shortName: 'BNI', color: '#ED7125' },
  { code: 'bri', name: 'BRI', shortName: 'BRI', color: '#00529B' },
  { code: 'mandiri', name: 'Mandiri', shortName: 'MDR', color: '#003B71' },
  { code: 'permata', name: 'Permata', shortName: 'PMT', color: '#005BAC' },
];

const currentDate = computed(() => {
  return new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
});

const dueDate = computed(() => {
  const date = new Date();
  date.setDate(date.getDate() + 1);
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
});

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(Math.round(price));
};

const submitOrder = () => {
  isSubmitting.value = true;

  const payload = {
    ...form,
    items: cartItems.value.map(item => ({
      id: item.id,
      quantity: item.quantity,
    })),
  };

  router.post('/checkout', payload, {
    onSuccess: () => {
      clearCart();
    },
    onFinish: () => {
      isSubmitting.value = false;
    },
  });
};
</script>
