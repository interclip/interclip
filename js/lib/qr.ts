import * as qrcode from "./generated/qr_code_wasm/generated/qr_code_wasm";

let loaded = false;

qrcode.default("/out/lib/qr_code_wasm/generated/qr_code_wasm_bg.wasm").then(() => {
    console.trace("Loaded QR code WASM module");
    loaded = true;
});

const waitForLoad = () => {
    return new Promise((resolve) => {
        const interval = setInterval(() => {
            if (loaded) {
                clearInterval(interval);
                resolve(true);
            }
        }, 100);
    });
};

export const generate = async (url: string) => {
    if (!loaded) {
        await waitForLoad();
    }
    const data = qrcode.generate_qr_code(url, "#ffffff", "#000000");
    return data;
}