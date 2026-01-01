// src/composables/useToast.js
import { reactive } from "vue";

export function useToast() {
  const toasts = reactive([]);

  const addToast = (message, type = "info", duration = 3000) => {
    const id = Date.now();
    toasts.push({ id, message, type });
    setTimeout(() => {
      const index = toasts.findIndex((t) => t.id === id);
      if (index !== -1) toasts.splice(index, 1);
    }, duration);
  };

  return { toasts, addToast };
}
