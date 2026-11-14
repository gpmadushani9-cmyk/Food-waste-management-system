
/**
 * Very small chart helper (no dependencies).
 * Supports simple bar charts. Not meant to replace full chart libs.
 */
export function renderBarChart(canvas, labels, values, options = {}) {
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  const w = canvas.width = (options.width || canvas.clientWidth || 600);
  const h = canvas.height = (options.height || 260);
  ctx.clearRect(0, 0, w, h);

  const padding = 36;
  const maxVal = Math.max(1, ...values.map(v => Number(v) || 0));
  const barW = (w - padding * 2) / Math.max(1, values.length);
  const baseY = h - padding;

  // axis
  ctx.strokeStyle = '#3b3b3b';
  ctx.lineWidth = 1;
  ctx.beginPath();
  ctx.moveTo(padding, padding);
  ctx.lineTo(padding, baseY);
  ctx.lineTo(w - padding, baseY);
  ctx.stroke();

  // title
  if (options.title) {
    ctx.fillStyle = '#111';
    ctx.font = '600 14px system-ui, -apple-system, Segoe UI, Roboto, Arial';
    ctx.fillText(options.title, padding, 18);
  }

  // bars
  values.forEach((v, i) => {
    const val = Number(v) || 0;
    const x = padding + i * barW + 6;
    const barH = (val / maxVal) * (baseY - padding - 12);
    const y = baseY - barH;
    ctx.fillStyle = '#2a66ff';
    ctx.fillRect(x, y, Math.max(2, barW - 12), barH);

    // labels (rotated if long)
    ctx.save();
    ctx.translate(x + (barW - 12)/2, baseY + 12);
    ctx.rotate(-Math.PI / 6);
    ctx.fillStyle = '#111';
    ctx.font = '11px system-ui, -apple-system, Segoe UI, Roboto, Arial';
    ctx.textAlign = 'center';
    ctx.fillText(String(labels[i] ?? ''), 0, 0);
    ctx.restore();
  });

  // y ticks
  ctx.fillStyle = '#111';
  ctx.font = '10px system-ui, -apple-system, Segoe UI, Roboto, Arial';
  for (let t = 0; t <= 4; t++) {
    const val = (maxVal * t) / 4;
    const y = baseY - ((baseY - padding) * t) / 4;
    ctx.fillText(val.toFixed(0), 6, y + 3);
    ctx.strokeStyle = '#e6e6e6';
    ctx.beginPath();
    ctx.moveTo(padding, y);
    ctx.lineTo(w - padding, y);
    ctx.stroke();
  }
}
