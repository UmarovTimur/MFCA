// Переадресация с рубрики языка на книги
// Пример
// mfca.uzlatin.com/category/az/ ==> mfca.uzlatin.com/category/az-book/
const langCategory = [
  "az",
  "kz",
  "ka",
  "kg",
  "ce",
  "ru",
  "tj",
  "tk",
  "uz",
  "ug",
];
let urlLangCategory = window.location.pathname.toString().slice(-3, -1);
for (let i = 0; i < langCategory.length; i++) {
  if (urlLangCategory == langCategory[i]) {
    let replaceUrl =
      `${window.location.origin.toString()}` +
      `${window.location.pathname.toString().slice(0, -1)}` +
      "-book";
    window.location.replace(replaceUrl);
    break;
  }
}

const categories = ["book", "audio", "video", "story"];
document.addEventListener("DOMContentLoaded", (e) => {
  let nodeLinks = document.querySelectorAll(".breadcrumbs__link");
  nodeLinks.forEach((el) => {
    let text = el.querySelector("span").textContent;
    if (categories.includes(text)) {
      el.querySelector("span").textContent =
        text[0].toUpperCase() + text.slice(1);
    }
  });
});
