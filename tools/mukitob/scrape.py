#!/usr/bin/env python3
"""
Scrape book metadata from the old site (mukitob.com) into JSON.

Output: data/books.json (git-ignored). Nothing heavy is downloaded: only HTML pages;
covers, documents and mp3 stay on mukitob.com and are linked, like in the existing posts.

Usage: python3 scrape.py [lang ...]    (default: az ka kg kz tj tm uz)
Resumable: books already in data/books.json are skipped.
"""
import html
import json
import os
import re
import sys
import time
import urllib.request

BASE = 'https://mukitob.com'
LANGS = ['az', 'ka', 'kg', 'kz', 'tj', 'tm', 'uz']
DELAY = 0.3
OUT = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'data', 'books.json')


def get(url):
	for attempt in range(4):
		try:
			req = urllib.request.Request(url, headers={'User-Agent': 'mfca-migration/1.0'})
			with urllib.request.urlopen(req, timeout=40) as r:
				data = r.read().decode('utf-8', 'replace')
			time.sleep(DELAY)
			return data
		except Exception as e:  # noqa: BLE001
			print('  retry', url, e, file=sys.stderr)
			time.sleep(2 * (attempt + 1))
	return None


def first(pattern, text, flags=re.S):
	m = re.search(pattern, text, flags)
	return m.group(1).strip() if m else ''


def text_of(fragment):
	return html.unescape(re.sub(r'<[^>]+>', '', fragment)).replace('\xa0', ' ').strip()


def list_ids(lang):
	ids = []
	page = 1
	while page < 60:
		h = get(f'{BASE}/books/{lang}/all_books.php?page={page}')
		found = re.findall(r'book\.php\?id_book=(\d+)', h or '')
		if not found:
			break
		ids += found
		page += 1
	return sorted(set(ids), key=int)


def scrape_book(lang, book_id):
	url = f'{BASE}/books/{lang}/book.php?id_book={book_id}'
	h = get(url)
	if not h or 'book-info__title' not in h:
		return None
	book = {
		'lang': lang,
		'id': int(book_id),
		'url': url,
		'title': text_of(first(r"<h1 class='book-info__title'>(.*?)</h1>", h)),
		'author': text_of(first(r"<p class='book-info__author'>(.*?)</p>", h)),
		'about': text_of(first(r"<p class='book-info__about'>(.*?)</p>", h)),
		'annotation': first(r"<p class='book-info__annotation'>(.*?)</p>", h),
		'cover': first(r"<img src='(/books/covers/[^']+)'[^>]*class='book-info__img'", h),
		'files': {},
		'tracks': [],
	}
	for fmt in re.findall(r"download\.php\?id_book=\d+&f=(\w+)'", h):
		d = get(f'{BASE}/books/{lang}/download.php?id_book={book_id}&f={fmt}')
		link = first(r'<a href="(/books/download/[^"]+)"', d or '')
		size = first(r'<small>[^<]*\(([\d.]+&nbsp;\w+)\)', d or '')
		if link:
			book['files'][fmt] = {'url': BASE + link, 'size': html.unescape(size).replace('\xa0', ' ')}
	if f'audio.php?id_book={book_id}' in h:
		a = get(f'{BASE}/books/{lang}/audio.php?id_book={book_id}') or ''
		for link, size in re.findall(r'<a href="(/books/download/[^"]+\.mp3)">[^<]*</a>\s*<font[^>]*>\[([^\]]+)\]', a):
			book['tracks'].append({'url': BASE + link, 'size': html.unescape(size).replace('\xa0', ' ')})
	return book


def main():
	langs = sys.argv[1:] or LANGS
	os.makedirs(os.path.dirname(OUT), exist_ok=True)
	books = json.load(open(OUT, encoding='utf-8')) if os.path.exists(OUT) else []
	done = {(b['lang'], b['id']) for b in books}
	for lang in langs:
		ids = list_ids(lang)
		print(f'{lang}: {len(ids)} books', flush=True)
		for book_id in ids:
			if (lang, int(book_id)) in done:
				continue
			book = scrape_book(lang, book_id)
			if book:
				books.append(book)
				json.dump(books, open(OUT, 'w', encoding='utf-8'), ensure_ascii=False, indent=1)
				print(f'  {lang}/{book_id} {book["title"][:40]} files={len(book["files"])} tracks={len(book["tracks"])}', flush=True)
			else:
				print(f'  {lang}/{book_id} FAILED', flush=True)


if __name__ == '__main__':
	main()
