import { reactive, computed, watch } from 'vue';

const CART_STORAGE_KEY = 'babuihan_cart';

function loadCart() {
  try {
    const data = localStorage.getItem(CART_STORAGE_KEY);
    return data ? JSON.parse(data) : [];
  } catch {
    return [];
  }
}

function saveCart(items) {
  localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(items));
}

const state = reactive({
  items: loadCart(),
  isOpen: false,
  toast: null,
});

watch(() => state.items, (items) => {
  saveCart(items);
}, { deep: true });

export function useCart() {
  const totalItems = computed(() =>
    state.items.reduce((sum, item) => sum + item.quantity, 0)
  );

  const subtotal = computed(() =>
    state.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
  );

  const tax = computed(() => subtotal.value * 0.11);
  const totalAmount = computed(() => subtotal.value + tax.value);

  function addToCart(product) {
    const existing = state.items.find(item => item.id === product.id);

    if (existing) {
      if (existing.quantity < product.stock) {
        existing.quantity++;
      }
    } else {
      state.items.push({
        id: product.id,
        name: product.name,
        price: product.price,
        image: product.image,
        stock: product.stock,
        category: product.category ? product.category.name : 'Uncategorized',
        quantity: 1,
      });
    }

    showToast(product.name);
  }

  function removeFromCart(productId) {
    const index = state.items.findIndex(item => item.id === productId);
    if (index !== -1) {
      state.items.splice(index, 1);
    }
  }

  function updateQuantity(productId, quantity) {
    const item = state.items.find(item => item.id === productId);
    if (item) {
      if (quantity <= 0) {
        removeFromCart(productId);
      } else if (quantity <= item.stock) {
        item.quantity = quantity;
      }
    }
  }

  function incrementQuantity(productId) {
    const item = state.items.find(item => item.id === productId);
    if (item && item.quantity < item.stock) {
      item.quantity++;
    }
  }

  function decrementQuantity(productId) {
    const item = state.items.find(item => item.id === productId);
    if (item) {
      if (item.quantity > 1) {
        item.quantity--;
      } else {
        removeFromCart(productId);
      }
    }
  }

  function clearCart() {
    state.items.splice(0, state.items.length);
  }

  function toggleDrawer() {
    state.isOpen = !state.isOpen;
  }

  function openDrawer() {
    state.isOpen = true;
  }

  function closeDrawer() {
    state.isOpen = false;
  }

  function showToast(productName) {
    state.toast = `${productName} ditambahkan ke keranjang!`;
    setTimeout(() => {
      state.toast = null;
    }, 2500);
  }

  return {
    items: computed(() => state.items),
    totalItems,
    subtotal,
    tax,
    totalAmount,
    isOpen: computed(() => state.isOpen),
    toast: computed(() => state.toast),
    addToCart,
    removeFromCart,
    updateQuantity,
    incrementQuantity,
    decrementQuantity,
    clearCart,
    toggleDrawer,
    openDrawer,
    closeDrawer,
  };
}
