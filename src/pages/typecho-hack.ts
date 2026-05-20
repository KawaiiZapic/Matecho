// oxlint-disable typescript/no-unsafe-call
// oxlint-disable typescript/no-unsafe-member-access
// oxlint-disable typescript/no-unsafe-return
// oxlint-disable typescript/no-explicit-any
// oxlint-disable typescript/no-unsafe-assignment

export let reloadScrollable: (bool: boolean) => void;
//@ts-expect-error hack
const oScrollableEditor: any = window.scrollableEditor;
//@ts-expect-error hack
window.scrollableEditor = (...args: any[]) => {
  reloadScrollable = oScrollableEditor(...args);
  return reloadScrollable;
};
