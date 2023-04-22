export { }; // This line is important to make this file an external module

declare global {
    interface String {
        trimTrailingSlash(): string;
        trimKnownProtocols(): string;
    }
}
