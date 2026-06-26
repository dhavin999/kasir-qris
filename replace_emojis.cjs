const fs = require('fs');
const path = require('path');

const faLink = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';

const emojiMap = {
    '☕': '<i class="fa-solid fa-mug-hot"></i>',
    '🧑‍💼': '<i class="fa-solid fa-user-tie"></i>',
    '🛒': '<i class="fa-solid fa-cart-shopping"></i>',
    '📋': '<i class="fa-solid fa-clipboard-list"></i>',
    '🖨️': '<i class="fa-solid fa-print"></i>',
    '🚪': '<i class="fa-solid fa-right-from-bracket"></i>',
    '🎉': '<i class="fa-solid fa-circle-check text-white"></i>',
    '🍳': '<i class="fa-solid fa-fire-burner"></i>',
    '🛍️': '<i class="fa-solid fa-bag-shopping"></i>',
    '🛎️': '<i class="fa-solid fa-bell-concierge"></i>',
    '💵': '<i class="fa-solid fa-money-bill-wave"></i>',
    '📊': '<i class="fa-solid fa-chart-line"></i>',
    '📁': '<i class="fa-solid fa-folder"></i>',
    '🍔': '<i class="fa-solid fa-burger"></i>',
    '📦': '<i class="fa-solid fa-box"></i>',
    '🪑': '<i class="fa-solid fa-chair"></i>',
    '🏷️': '<i class="fa-solid fa-tags"></i>',
    '👥': '<i class="fa-solid fa-users"></i>',
    '➕': '<i class="fa-solid fa-plus"></i>',
    '✏️': '<i class="fa-solid fa-pen-to-square"></i>',
    '🗑️': '<i class="fa-solid fa-trash"></i>',
    '✅': '<i class="fa-solid fa-check"></i>',
    '❌': '<i class="fa-solid fa-xmark"></i>',
    '🔙': '<i class="fa-solid fa-arrow-left"></i>',
    '💾': '<i class="fa-solid fa-floppy-disk"></i>',
    '🔍': '<i class="fa-solid fa-magnifying-glass"></i>',
    '💰': '<i class="fa-solid fa-coins"></i>',
    '📈': '<i class="fa-solid fa-arrow-trend-up"></i>',
    '📉': '<i class="fa-solid fa-arrow-trend-down"></i>',
    '💳': '<i class="fa-solid fa-credit-card"></i>',
    '🧾': '<i class="fa-solid fa-receipt"></i>',
    '📱': '<i class="fa-solid fa-mobile-screen-button"></i>',
    '⏳': '<i class="fa-solid fa-hourglass-half"></i>',
    '💬': '<i class="fa-regular fa-comment-dots"></i>',
    '👨‍💼': '<i class="fa-solid fa-user-tie"></i>',
    '👑': '<i class="fa-solid fa-crown"></i>'
};

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(file => {
        file = path.join(dir, file);
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) {
            results = results.concat(walk(file));
        } else if (file.endsWith('.blade.php')) {
            results.push(file);
        }
    });
    return results;
}

const files = walk(path.join(__dirname, 'resources', 'views'));

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    let changed = false;

    // Check for emojis
    for (const [emoji, icon] of Object.entries(emojiMap)) {
        if (content.includes(emoji)) {
            // Replace globally
            content = content.split(emoji).join(icon);
            changed = true;
        }
    }

    // Add fontawesome if it has a <head> and doesn't have font-awesome
    if (content.includes('</head>') && !content.includes('font-awesome')) {
        content = content.replace('</head>', `    ${faLink}\n</head>`);
        changed = true;
    }

    if (changed) {
        fs.writeFileSync(file, content, 'utf8');
        console.log(`Updated ${file}`);
    }
});

console.log('Done!');
