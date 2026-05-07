interface CommentResult {
  root: HTMLHtmlElement;
  success: boolean;
  error: string;
}

export async function sendComment(
  url: string,
  data: FormData
): Promise<CommentResult> {
  const token = window.__MATECHO_ANTI_SPAM__;
  if (typeof token === "string") {
    data.set("_", token);
  }
  data.set("receiveMail", "yes");
  const req = await fetch(url, {
    body: data,
    method: "POST",
    credentials: "same-origin"
  });

  const resp = await req.text();
  const root = Object.assign(document.createElement("html"), {
    innerHTML: resp
  }) as HTMLHtmlElement;
  if (req.status === 200) {
    return {
      root,
      success: true,
      error: ""
    };
  } else {
    return {
      root,
      success: false,
      error: (root.querySelector(".container") as HTMLDivElement)?.innerText
    };
  }
}
