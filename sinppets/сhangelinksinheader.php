// получение url страницы
let urlForEachPageSection = new URL(`${document.location.href}`);
console.log(urlForEachPageSection.searchParams.get('section'));
console.log(document.getElementById('id-for-each-of-post').innerHTML);
if (document.getElementById('id-for-each-of-post') == null) {
	console.error('Error - 101 [id-for-each-of-post]');
} else if (!urlForEachPageSection.searchParams.get('section')) {
	urlForEachPageSection.searchParams.set('section', `${document.getElementById('id-for-each-of-post').innerHTML}`);
	document.location.href = urlForEachPageSection.href;
}
