use qrcode::{QrCode, Version, EcLevel};
use qrcode::render::svg;
use wasm_bindgen::prelude::*;
use serde::Serialize;

#[derive(Serialize)]
struct Svg {
    content: String,
}

#[wasm_bindgen]
pub fn test() -> Result<String, String> {
    Ok("Hello".to_string())
}

#[wasm_bindgen]
pub fn generate_qr_code(text: &str, light_color: &str, dark_color: &str) -> Result<String, String> {
    let code = QrCode::with_version(text, Version::Normal(2), EcLevel::L)
        .map_err(|e| format!("QR code generation failed: {}", e))?;

    let image = code.render::<svg::Color>()
        .min_dimensions(100, 100)
        .dark_color(svg::Color(dark_color))
        .light_color(svg::Color(light_color))
        .build();

    Ok(image)
}

