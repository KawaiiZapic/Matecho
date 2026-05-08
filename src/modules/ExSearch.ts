import { Dropdown, List, ListItem } from "mdui";
import type { TextField } from "mdui";
import { mGlobal } from "@/utils/global";
import type { IExSearchData } from "@/utils/insight";
import { parseKeywords, search } from "@/utils/insight";

export async function initExSearchEnhanced(url: string) {
  function addMenuItem(
    title: string,
    desc: string,
    link?: string,
    keywords?: string
  ) {
    const el = new ListItem();
    const wrapper = Object.assign(document.createElement("div"), {
      slot: "custom"
    });
    el.appendChild(wrapper);
    const titleEl = Object.assign(document.createElement("div"), {
      innerHTML: title,
      className: "search-title"
    });
    const descEl = Object.assign(document.createElement("div"), {
      innerHTML: desc,
      className: "search-desc"
    });
    if (keywords) {
      const keyword = parseKeywords(keywords)
        .map(v => ({
          word: v,
          index: descEl.innerHTML.toUpperCase().indexOf(v)
        }))
        .sort((a, b) => a.index - b.index)[0];
      if (keyword.index != -1) {
        const s = descEl.innerHTML.substring(0, keyword.index).slice(-7);
        const w = descEl.innerHTML.substring(
          keyword.index,
          keyword.index + keyword.word.length
        );
        const e = descEl.innerHTML.substring(
          keyword.index + keyword.word.length
        );
        descEl.innerHTML = s + `<span class="search-keyword">${w}</span>` + e;
      }
    }
    wrapper.appendChild(titleEl);
    wrapper.appendChild(descEl);
    if (!link) {
      el.disabled = true;
    } else {
      el.addEventListener("click", () => {
        mGlobal.pjax?.loadUrl(link);
        dropdown.open = false;
      });
    }
    list.appendChild(el);
  }
  const searchbar = document.querySelector<TextField>(
    "#matecho-top-search-bar"
  );
  if (!searchbar) return;
  let data: IExSearchData;
  try {
    data = (await (await fetch(url)).json()) as IExSearchData;
  } catch (_) {
    return;
  }
  const dropdown = new Dropdown();
  dropdown.trigger = "manual";
  dropdown.placement = "bottom-start";
  const list = new List();
  list.classList.add("matecho-search-menu");
  // @ts-expect-error prevent MDUI focus on panel when dropdown is opened, which will cause searchbar focus loss
  list.focus = null;
  searchbar.after(dropdown);
  searchbar.slot = "trigger";
  dropdown.appendChild(searchbar);
  dropdown.appendChild(list);
  searchbar.addEventListener("keydown", e => {
    if (e.key === "Enter") {
      e.preventDefault();
      const el = list.querySelector<ListItem>("mdui-list-item[active]");
      if (el && dropdown.open) {
        el.click();
        searchbar.value = "";
        searchbar.blur();
      }
    } else if (e.key === "ArrowDown") {
      e.preventDefault();
      const el = list.querySelector<ListItem>("mdui-list-item[active]");
      if (!el) {
        (list.firstElementChild as ListItem).active = true;
      }
      if (el?.nextElementSibling) {
        (el.nextElementSibling as ListItem).active = true;
        el.active = false;
      } else if (el === list.lastElementChild) {
        (list.firstElementChild as ListItem).active = true;
        el!.active = false;
      }
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      const el = list.querySelector<ListItem>("mdui-list-item[active]");
      if (!el) {
        (list.lastElementChild as ListItem).active = true;
      }
      if (el?.previousElementSibling) {
        (el.previousElementSibling as ListItem).active = true;
        el.active = false;
      } else if (el === list.firstElementChild) {
        (list.lastElementChild as ListItem).active = true;
        el!.active = false;
      }
    }
    if (e.defaultPrevented) {
      setTimeout(() => {
        const target = list.querySelector<ListItem>("mdui-list-item[active]");
        if (target) {
          const listRect = list.getBoundingClientRect();
          const targetRect = target.getBoundingClientRect();
          if (targetRect.top < listRect.top) {
            list.scrollTo({
              top: list.scrollTop - (listRect.top - targetRect.top),
              behavior: "smooth"
            });
          } else if (targetRect.bottom > listRect.bottom) {
            list.scrollTo({
              top: list.scrollTop + (targetRect.bottom - listRect.bottom),
              behavior: "smooth"
            });
          }
        }
      });
    }
  });
  searchbar.addEventListener("focus", () => {
    // When user click on Clear button of search input, focus will trigger immediately
    // But the input is not clear immediately, wait a bit for it to be cleared.
    setTimeout(() => {
      if (searchbar.value.length > 0) {
        dropdown.open = true;
      }
    }, 100);
  });
  searchbar.addEventListener(
    "input",
    () => {
      if (searchbar.value.length == 0) return void (dropdown.open = false);
      list.innerHTML = "";
      const result = search(data, searchbar.value);
      result.posts.forEach(post => {
        addMenuItem(
          post.title || "无标题",
          post.text,
          post.path,
          searchbar.value
        );
      });
      result.pages.forEach(page => {
        addMenuItem(
          page.title || "无标题",
          page.text,
          page.path,
          searchbar.value
        );
      });
      result.tags.forEach(tag => {
        addMenuItem(tag.name, tag.slug, tag.permalink, searchbar.value);
      });
      result.categories.forEach(category => {
        addMenuItem(
          category.name,
          category.slug,
          category.permalink,
          searchbar.value
        );
      });
      if (list.childNodes.length == 0) {
        addMenuItem("无搜索结果", "尝试更换搜索词");
      } else {
        (list.firstChild as ListItem).active = true;
      }
      dropdown.open = true;
    },
    { passive: true }
  );
}

export function ExSearchBasicIntegration() {
  const pjax = mGlobal.pjax;
  if (!pjax) throw new Error("Pjax object is null");
  window.ExSearchCall = item => {
    if (item && item.length) {
      const url = item.attr("data-url");
      if (url) {
        document.querySelector<HTMLElement>(".ins-close")?.click();
        pjax.loadUrl(url);
      }
    }
  };
}
