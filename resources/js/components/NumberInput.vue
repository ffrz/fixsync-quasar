<template>
  <q-input
    v-model="formattedValue"
    label="Enter a number"
    @input="formatInput"
    @keydown="filterKeys"
  />
</template>

<script>
export default {
  data() {
    return {
      rawValue: "", // Raw number without formatting
      formattedValue: "", // Display value with locale formatting
    };
  },
  methods: {
    formatInput(value) {
      // Remove non-numeric characters (except for the locale decimal separator)
      const numericValue = value.replace(/[^\d.-]/g, "");

      // Convert to a number
      const number = parseFloat(numericValue);

      if (!isNaN(number)) {
        // Format the number to the locale
        this.rawValue = number; // Save the raw numeric value
        this.formattedValue = new Intl.NumberFormat(navigator.language).format(
          number
        );
      } else {
        // If the value is not a valid number, reset to raw value
        this.formattedValue = "";
        this.rawValue = "";
      }
    },
    filterKeys(event) {
      // Allow navigation and editing keys
      const allowedKeys = ["Backspace", "Delete", "ArrowLeft", "ArrowRight", "Tab"];
      const isNumeric = /^[0-9.,-]$/; // Allow numbers, decimal, and negative sign

      if (!isNumeric.test(event.key) && !allowedKeys.includes(event.key)) {
        event.preventDefault();
      }
    },
  },
};
</script>
