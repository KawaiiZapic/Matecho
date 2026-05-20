import { initKaTeX, initMermaid, initPrism, initShiki } from "./post";
import "@/style/editor.less";
import { reloadScrollable } from "./typecho-hack";

function init() {
  if (import.meta.env.DEV) {
    // Unocss is conflict with typecho default stylesheet
    // this is not happening in production because all unocss classes is mangled
    document.querySelector("style[data-vite-dev-id='/__uno.css']")?.remove();
  }
  const int = setInterval(() => {
    const preview = document.querySelector<HTMLDivElement>("#wmd-preview");
    if (!preview) return;
    clearInterval(int);
    const heavyOpDelay = Promise.resolve();
    const { KaTeX, Highlighter, Mermaid } = window.__MATECHO_OPTIONS__;
    const ob = new MutationObserver(() => {
      if (window.getComputedStyle(preview).display === "none") return;
      const linesMapper = new Map<Element, Array<Element>>();
      document.querySelectorAll("#wmd-preview pre code").forEach(codeEl => {
        const lines = Array.from(codeEl.querySelectorAll(".line"));
        if (codeEl.classList.contains("mermaid") && Mermaid) {
          codeEl.parentElement!.before(...lines);
        } else {
          lines.forEach(e => e.remove());
          linesMapper.set(codeEl, lines);
        }
      });
      const pending: Promise<unknown>[] = [];
      if (KaTeX) pending.push(initKaTeX(preview, heavyOpDelay));
      preview.querySelectorAll("pre code[class]").forEach(el => {
        el.classList.remove("focus");
        let lang = el.className.split(" ").find(v => v != "focus");
        if (!lang) return;
        lang = lang
          .split("-")
          .filter(v => v != "r")
          .join("-");
        el.parentElement?.classList.add("lang-" + lang);
        el.classList.add("lang-" + lang);
      });
      if (Mermaid) pending.push(initMermaid(preview, heavyOpDelay));
      if (Highlighter == "Prism") {
        pending.push(initPrism(preview, heavyOpDelay));
      } else if (Highlighter == "Shiki") {
        pending.push(initShiki(preview, heavyOpDelay));
      }
      void Promise.allSettled(pending).then(() => {
        linesMapper.keys().forEach(codeEl => {
          const lines = linesMapper.get(codeEl);
          linesMapper.delete(codeEl);
          if (!lines) return;
          codeEl.prepend(lines.shift()!);
          Array.from(codeEl.childNodes).forEach(node => {
            if (node.textContent?.endsWith("\n")) {
              const line = lines.shift();
              if (line) {
                node.after(line);
              }
            } else if (node.textContent?.startsWith("\n")) {
              const line = lines.shift();
              if (line) {
                node.before(line);
              }
            }
          });
        });
        reloadScrollable?.(true);
      });
    });
    ob.observe(preview, {
      childList: true,
      attributes: true
    });
  });
}

init();
