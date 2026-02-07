// ===== Laravel Core =====
import "./bootstrap.js";

// ===== TailAdmin Core =====
import Alpine from "alpinejs";
// import persist from "@alpinejs/persist";

// Plugins / Libraries
import "jsvectormap/dist/jsvectormap.min.css";
import "flatpickr/dist/flatpickr.min.css";
import "dropzone/dist/dropzone.css";

// Import main TailAdmin JS file
import "./tailadmin/index.js";

// Init Alpine
// Alpine.plugin(persist);
// window.Alpine = Alpine;
// Alpine.start();

console.log("✅ Laravel + TailAdmin fully integrated!");
