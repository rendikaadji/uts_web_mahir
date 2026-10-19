import { Html5Qrcode, Html5QrcodeSupportedFormats } from 'html5-qrcode';

// Expose Html5Qrcode globally for interactive scanner interfaces
if (typeof window !== 'undefined') {
    window.Html5Qrcode = Html5Qrcode;
    window.Html5QrcodeSupportedFormats = Html5QrcodeSupportedFormats;
}
