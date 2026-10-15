
const form = document.getElementById('calculator-form');
const display = document.getElementById('display');

form.addEventListener('click', async function (event) {
const button = event.target.closest('button[name="action"]');

if (!button) return;

event.preventDefault();

const formData = new FormData();
formData.append('action', button.value);

try {
        const response = await fetch('calculator.php', 
        {
            method: 'POST',
            headers: 
            {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        if (!response.ok) 
        {
            throw new Error('Server error: ' + response.status);
        }

        const data = await response.json();
        display.textContent = data.display;
    } 
    catch (error) 
    {
        console.error(error);
        display.textContent = 'Error';
    }
});


const fakeAd = document.getElementById('fakeAd');
const closeAd = document.getElementById('closeAd');
const adBuy = document.getElementById('adBuy');

const ads = [
    {
        image: 'rene.jpg',
        headline: '!!! MAMA !!!',
        title: 'MAHAL MO BA SI RENE?',
        tagline: 'A CHANCE TO WIN A TICKET TO HEAVEN',
        oldPrice: 'WAS $19.99',
        price: 'NOW $4.99!',
        description: '100% fictional. 200% retro.'
    },
    {
        image: 'bbm.jpg',
        headline: 'ADIK ADIK ADIK',
        title: 'BOBO AKO',
        tagline: 'NAG MANA AKO SA TATAY KO!',
        oldPrice: '₱20 BIGAS?',
        price: 'NOW ₱60',
        description: 'everything is political'
    },
    {
        image: 'balde.jpg',
        headline: 'MAKINIG SA BOBONG PANGULO',
        title: 'BALDE',
        tagline: 'maging ready sa super El niño.',
        oldPrice: 'WAS ₱150',
        price: 'NOW ₱100',
        description: 'Imaginary batteries not included.'
    }
];

const randomAd = ads[Math.floor(Math.random() * ads.length)];

document.getElementById('adHeadline').textContent = randomAd.headline;
document.getElementById('adTitle').textContent = randomAd.title;
document.getElementById('adTagline').textContent = randomAd.tagline;
document.getElementById('adOldPrice').textContent = randomAd.oldPrice;
document.getElementById('adPrice').textContent = randomAd.price;
document.getElementById('adDescription').textContent =
    randomAd.description;

document.getElementById('adImage').src = randomAd.image;
document.getElementById('adImage').alt = randomAd.title;

let adClosed = false;

closeAd.addEventListener('click', function () {
    adClosed = true;
    fakeAd.classList.remove('ad-show');
    fakeAd.classList.add('ad-hidden');
});

adBuy.addEventListener('click', function () {
    adClosed = true;
    fakeAd.classList.remove('ad-show');
    fakeAd.classList.add('ad-hidden');

    alert('SURPRISE! This is a fictional retro ad!');
});

// Random delay: between 5 and 15 seconds
const randomDelay = Math.floor(Math.random() * 10000) + 5000;

setTimeout(function () {
    if (!adClosed) {
        fakeAd.classList.remove('ad-hidden');
        fakeAd.classList.add('ad-show');
    }
}, randomDelay);

