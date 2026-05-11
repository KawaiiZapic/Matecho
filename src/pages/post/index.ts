import { initComments } from "./comment";
import { handlePasswordForm } from "./locked";
import { initCodeBlockAction, initPrism, initShiki } from "./code-block";

import "@/style/post.less";
import "virtual:components/post";

import "mdui/components/button-icon";
import "@mdui/icons/copy-all";

function initFancybox(container: HTMLElement) {
  return Promise.all([
    import("@fancyapps/ui/dist/fancybox/fancybox.css"),
    Promise.all([
      import("@fancyapps/ui"),
      import("@fancyapps/ui/dist/fancybox/l10n/zh_CN")
    ]).then(([{ Fancybox: fb }, { zh_CN }]) => {
      container.querySelectorAll<HTMLImageElement>("img").forEach(v => {
        v.setAttribute("data-fancybox", "article");
        if (v.alt ?? v.title) {
          v.setAttribute("data-caption", v.alt ?? v.title);
        }
      });
      fb.bind("[data-fancybox]", {
        l10n: zh_CN
      });
    })
  ]);
}

export function initKaTeX(container: HTMLElement, heavyOpDelay: Promise<void>) {
  return Promise.all([
    import("katex/dist/katex.css"),
    import("katex/contrib/auto-render").then(
      ({ default: renderMathInElement }) => {
        void heavyOpDelay.then(() => {
          renderMathInElement(container, {
            delimiters: [
              { left: "$$", right: "$$", display: true },
              { left: "$", right: "$", display: false }
            ]
          });
        });
      }
    )
  ]);
}

export async function initMermaid(
  container: HTMLElement,
  heavyOpDelay: Promise<void>
) {
  const nodes = container.querySelectorAll<HTMLElement>(
    "pre > code.lang-mermaid"
  );
  const mapNodes = Array.from(nodes).map(node => {
    const parent = node.parentElement!;
    parent.classList.add("mermaid");
    parent.setAttribute("data-processed", "");
    parent.innerHTML = node.innerHTML || "";
    return parent;
  });
  const [{ default: mermaid }, { default: ZenUML }] = await Promise.all([
    import("mermaid"),
    import("@mermaid-js/mermaid-zenuml")
  ]);
  await mermaid.registerExternalDiagrams([ZenUML], {
    lazyLoad: true
  });
  mermaid.initialize({
    startOnLoad: false
  });
  await heavyOpDelay;
  void mermaid.run({
    nodes: mapNodes
  });
}

function countMoney(str: string) {
  let count = -1;
  let index = -2;
  for (; index != -1; count++, index = str.indexOf("$", index + 1));
  return count;
}

function initArticle(article: HTMLElement, _heavyOpDelay?: Promise<void>) {
  const heavyOpDelay = _heavyOpDelay ?? Promise.resolve();
  const { Highlighter, FancyBox, KaTeX, Mermaid } = window.__MATECHO_OPTIONS__;
  // enforce Mermaid processed before code block
  // this is required to prevent codeblock logic break Mermaid.
  // initMermaid will modify DOM struct make code block logic cannot process it as code block
  if (Mermaid && article.querySelector("pre > code.lang-mermaid")) {
    void initMermaid(article, heavyOpDelay);
  }
  initCodeBlockAction(article);
  if (article.querySelector("pre > code[class*=lang-]")) {
    if (Highlighter == "Prism") {
      void initPrism(article, heavyOpDelay);
    } else if (Highlighter == "Shiki") {
      void initShiki(article, heavyOpDelay);
    }
    void heavyOpDelay.then(() => {});
  }
  if (FancyBox && article.querySelector("img")) {
    void initFancybox(article);
  }
  if (KaTeX) {
    const count$ = countMoney(article.innerText);
    if (article.innerText.includes("$")) {
      const excludeText = Array.from(
        article.querySelectorAll<HTMLElement>(
          "script, noscript, style, textarea, pre, code, option"
        )
      )
        .map(v => v.innerText)
        .join("");
      const excluded$ = countMoney(excludeText);
      if (excluded$ < count$) {
        void initKaTeX(article, heavyOpDelay);
      }
    }
  }
}

export function init(el: HTMLElement) {
  initComments(el);
  const article = el.querySelector<HTMLElement>("article.mdui-prose");
  if (article) {
    if (el.classList.contains("slide-in")) {
      const waiting = new Promise<void>(res => {
        const cb = () => res();
        el.addEventListener("animationend", cb, { once: true });
        // Fallback timer based trigger for browser that not support animationend event
        setTimeout(() => {
          cb();
          el.removeEventListener("animationend", cb);
        }, 500);
      });
      initArticle(article, waiting);
    } else {
      initArticle(article);
    }
  }
  const password = document.querySelector<HTMLFormElement>(
    "form#matecho-password-form"
  );
  if (password) {
    handlePasswordForm(password);
  }
}

export { initPrism, initShiki };
