/// <reference types="svelte" />
/// <reference types="vite/client" />

declare module '*.svelte' {
  const content: any;
  export default content;
}