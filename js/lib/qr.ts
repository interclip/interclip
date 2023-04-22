import * as qrcode from "./qr_code_wasm/generated/qr_code_wasm";

export const generate = async (url: string) => {
    await qrcode.default("qr_code_wasm/generated/qr_code_wasm_bg.wasm").then((qrcode) => {
        console.log(qrcode);
    });

    console.log(qrcode.test());

    const data = qrcode.generate_qr_code(url, "#ffffff", "#000000");
    return data;
}