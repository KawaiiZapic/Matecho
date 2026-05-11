import type {
  NavigationDrawer,
  Button,
  ButtonIcon,
  TextField,
  LayoutMain,
  TopAppBar
} from "mdui";

import "virtual:uno.css";
import { observeResize } from "mdui/functions/observeResize";
import { breakpoint } from "mdui/functions/breakpoint";
import { mGlobal } from "@/utils/global";
import Pjax from "pjax";
import np from "nprogress";

import "@mdui/icons/insert-drive-file";
import "@mdui/icons/link";

import "virtual:components-custom-icon";
import "virtual:components/header";
import "virtual:components/functions";
import "virtual:components/sidebar";
import "virtual:components/footer";
import {
  ExSearchBasicIntegration,
  initExSearchEnhanced
} from "./modules/ExSearch";

interface IInit {
  init?: (el: HTMLElement) => void | Promise<void>;
  destroy?: (el: HTMLElement) => void | Promise<void>;
}

function loadPageScript(type: string): Promise<IInit> {
  switch (type) {
    case "post":
    case "page":
      return import("@/pages/post");
    case "page-links":
      return import("@/pages/links");
    default:
      if (type.startsWith("page-")) {
        return import("@/pages/post");
      } else {
        return import("@/pages/index");
      }
  }
}

function toHexColor(color: string): string {
  return (
    "#" +
    color
      .split(",")
      .map(v =>
        parseInt(v.replace("rgb(", "").replace(")", "").trim()).toString(16)
      )
      .join("")
  );
}

function initOnce() {
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => initOnce());
    return;
  }

  document.querySelector("#m-loading-wrapper")?.remove();
  // app bar title will have animation in first time loaded
  setTimeout(() => {
    document
      .getElementById("matecho-app-bar-title")
      ?.style.setProperty("display", "");
  }, 300);
  // Drawer
  const drawer = document.querySelector<NavigationDrawer>("#matecho-drawer");
  const topBtn = document.querySelector<Button>("#matecho-drawer-btn");
  const mainWrapper = document.querySelector<LayoutMain>("#matecho-main");

  if (!drawer || !topBtn || !mainWrapper) return;

  topBtn.addEventListener("click", () => {
    drawer.open = !drawer.open;
  });

  document.querySelector<HTMLMetaElement>('meta[name="theme-color"]')!.content =
    toHexColor(
      window
        .getComputedStyle(document.documentElement)
        .getPropertyValue("background-color")
    );

  // Search bar
  const searchBtn = document.querySelector(
    "#matecho-top-search-btn"
  ) as ButtonIcon;
  const searchInput = document.querySelector(
    "#matecho-top-search-bar"
  ) as TextField;
  const searchHiddenInput = document.querySelector(
    "form input[name='s']"
  ) as HTMLInputElement;
  const searchForm = searchInput.parentElement as HTMLFormElement;
  searchBtn.addEventListener("click", () => {
    if (searchInput.disabled) {
      searchInput.disabled = false;
      setTimeout(() => searchInput.focus());
    } else {
      searchForm.requestSubmit();
    }
  });

  searchInput.addEventListener("blur", () => {
    if (!searchInput.value) {
      searchInput.disabled = true;
    }
  });

  searchForm.addEventListener("submit", () => {
    searchHiddenInput.value = searchInput.value;
  });

  const signal = {
    resolve: undefined! as (value: string) => void,
    promise: undefined! as Promise<string>
  };

  let PjaxBackward = false;
  let cleanUpCb: undefined | ((el: HTMLElement) => unknown) = void 0;

  mGlobal.pjax = new Pjax({
    selectors: [
      "head > title",
      "#matecho-pjax-main",
      "#matecho-app-bar-title__inner",
      "#matecho-sidebar-list",
      "meta[name=matecho-template]"
    ],
    cacheBust: false,
    switches: {
      "meta[name=matecho-template]": function (oldEl: Element, el: Element) {
        signal.resolve((el as HTMLMetaElement).content);
        oldEl.replaceWith(el);
        this.onSwitch(oldEl, el);
      },
      // eslint-disable-next-line @typescript-eslint/no-misused-promises
      "#matecho-pjax-main": async function (oldEl: Element, el: Element) {
        const type = await signal.promise;
        const scripts = await loadPageScript(type);
        await cleanUpCb?.(oldEl as HTMLElement);
        oldEl.replaceWith(el);
        const wrapper =
          document.querySelector<HTMLDivElement>("#matecho-pjax-main");
        const main = document.querySelector<HTMLDivElement>("#matecho-main");
        if (wrapper && main) {
          const className = PjaxBackward ? "slide-out" : "slide-in";
          main.style.overflow = "hidden";
          wrapper.addEventListener(
            "animationend",
            () => {
              wrapper.classList.remove(className);
              main.style.overflow = "";
            },
            { once: true }
          );
          wrapper.classList.add(className);
        }
        await scripts.init?.(el as HTMLDivElement);
        cleanUpCb = scripts.destroy;
        this.onSwitch(oldEl, el);
      }
    }
  });

  document.addEventListener("pjax:send", () => {
    drawer.open = false;
  });

  document.addEventListener("pjax:success", (() => {
    void init();
  }) as EventListener);

  document.addEventListener("pjax:complete", e => {
    const scrollPos = (e as PjaxEvent).scrollPos;
    if (scrollPos) {
      setTimeout(() => {
        document.scrollingElement?.scrollTo({
          left: scrollPos[0],
          top: scrollPos[1]
        });
      }, 0);
    }
    np.done();
  });
  document.addEventListener("pjax:send", e => {
    PjaxBackward = (e as PjaxEvent).backward === true;
    np.start();
    if (breakpoint().down("md")) {
      drawer.open = false;
    }
    signal.promise = new Promise<string>(resolve => {
      signal.resolve = resolve;
    });
  });

  document.addEventListener("pjax:error", ((e: PjaxEvent) => {
    const newUrl = e.request?.responseURL;
    if (newUrl) {
      location.href = newUrl;
    } else {
      location.reload();
    }
  }) as EventListener);

  const type = (
    document.querySelector("meta[name=matecho-template]") as HTMLMetaElement
  ).content;

  if (window.__MATECHO_OPTIONS__.ExSearch.length > 0) {
    void initExSearchEnhanced(window.__MATECHO_OPTIONS__.ExSearch);
  } else {
    ExSearchBasicIntegration();
  }

  void loadPageScript(type)
    .then(i => {
      return i.init?.(document.querySelector("#matecho-pjax-main")!);
    })
    .then(() => init());
}

function handleLabelShrink(el: HTMLElement) {
  const appBar = document.querySelector("#matecho-app-bar") as TopAppBar;
  const inner = el.querySelector(
    "#matecho-app-bar-large-label__inner"
  ) as HTMLElement;
  if (!inner) return;
  const labelShrinkOb = new MutationObserver(() => {
    if (!document.body.contains(el)) {
      labelShrinkOb.disconnect();
      ro.unobserve();
      return;
    }
    if (appBar.shrink) {
      el.classList.add("shrink");
    } else {
      el.classList.remove("shrink");
    }
  });
  labelShrinkOb.observe(appBar, {
    attributes: true,
    attributeFilter: ["shrink"]
  });
  const ro = observeResize(inner, e => {
    const h = e.contentRect.height;
    const scroll = document.scrollingElement!;
    appBar.scrollThreshold = h / 2;
    if (h > scroll.scrollHeight - window.screen.availHeight) {
      appBar.scrollBehavior = undefined;
    } else {
      appBar.scrollBehavior = "shrink";
    }
  });
  np.configure({
    showSpinner: false,
    trickle: true
  });
}

function init() {
  const header = document.getElementById("matecho-app-bar-large-label");
  if (header) {
    handleLabelShrink(header);
  }
}

initOnce();
console.log(
  `%c Matecho %c By Zapic \n`,
  "color: #fff; background: #E91E63; padding:5px 0;",
  "color: #000;background: #efefef; padding:5px 0;",
  `${__BUILD_COMMIT_ID__} @ ${__BUILD_DATE__} `
);
