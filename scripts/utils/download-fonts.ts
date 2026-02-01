import { mkdirSync, existsSync, writeFileSync } from "fs";
// @ts-ignore
import path from "path";

const GOOGLE_FONTS_CSS_URL =
  "https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Oswald:wght@500;700&display=swap";

const OUTPUT_DIR = "./fonts";
const CSS_OUT = path.join(OUTPUT_DIR, "fonts.css");

function normalizeFamily(name) {
  return name.replace(/\s+/g, "");
}

function safeCssString(s) {
  return s.replace(/\\/g, "\\\\").replace(/"/g, '\\"');
}

async function main() {
  if (!existsSync(OUTPUT_DIR)) mkdirSync(OUTPUT_DIR, { recursive: true });

  const cssResp = await fetch(GOOGLE_FONTS_CSS_URL);
  if (!cssResp.ok) {
    throw new Error(`Failed to fetch CSS: ${cssResp.status} ${cssResp.statusText}`);
  }
  const css = await cssResp.text();

  const fontFaceRegex = /@font-face\s*{([^}]+)}/g;
  const blocks = Array.from(css.matchAll(fontFaceRegex));

  const entries = [];
  const variantCounters = new Map();

  for (const m of blocks) {
    const body = m[1];

    const family = body.match(/font-family:\s*'([^']+)'/)?.[1];
    const weight = body.match(/font-weight:\s*(\d+)/)?.[1];
    const style = body.match(/font-style:\s*([^;]+)/)?.[1]?.trim();
    const url = body.match(/src:\s*url\(([^)]+)\)/)?.[1];
    const unicodeRange = body.match(/unicode-range:\s*([^;]+)/)?.[1]?.trim() ?? "";
    const display = body.match(/font-display:\s*([^;]+)/)?.[1]?.trim() ?? "swap";
    const stretch = body.match(/font-stretch:\s*([^;]+)/)?.[1]?.trim();

    if (!family || !weight || !style || !url) continue;

    const key = `${family}|${style}|${weight}`;
    const index = (variantCounters.get(key) ?? 0) + 1;
    variantCounters.set(key, index);

    const filename =
      `${normalizeFamily(family)}-${style}-${weight}-${index}.woff2`;
    const filepath = path.join(OUTPUT_DIR, filename);

    if (!existsSync(filepath)) {
      const fontResp = await fetch(url);
      if (!fontResp.ok) {
        throw new Error(`Failed to fetch font ${url}: ${fontResp.status}`);
      }
      const buf = new Uint8Array(await fontResp.arrayBuffer());
      writeFileSync(filepath, buf);
      console.log(`Downloaded: ${filename}`);
    } else {
      console.log(`Skipped (exists): ${filename}`);
    }

    entries.push({
      family,
      style,
      weight,
      display,
      stretch,
      unicodeRange,
      localPath: `/fonts/${filename}`,
    });
  }

  const header = `/* Auto-generated */\n`;
  const cssOut =
    header +
    entries
      .map((e) => {
        const lines = [];
        lines.push(`@font-face {`);
        lines.push(`  font-family: "${safeCssString(e.family)}";`);
        lines.push(`  font-style: ${e.style};`);
        lines.push(`  font-weight: ${e.weight};`);
        if (e.stretch) lines.push(`  font-stretch: ${e.stretch};`);
        lines.push(`  font-display: ${e.display};`);
        lines.push(`  src: url("${e.localPath}") format("woff2");`);
        if (e.unicodeRange) {
          lines.push(`  unicode-range: ${e.unicodeRange};`);
        }
        lines.push(`}`);
        return lines.join("\n");
      })
      .join("\n\n") +
    "\n";

  writeFileSync(CSS_OUT, cssOut, "utf8");
  console.log(`\nWrote: ${CSS_OUT}`);
}

main().catch((err) => {
  console.error(err);
  process.exitCode = 1;
});

