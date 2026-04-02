import React, { useEffect, useState } from 'react';

const heroStats = [
    { value: '24/7', label: 'Sajian hangat', icon: 'fa-clock' },
    { value: '4+', label: 'Pilihan minum', icon: 'fa-mug-hot' },
    { value: 'Segar', label: 'Rasa pilihan', icon: 'fa-bolt' },
];

const featureCards = [
    {
        icon: 'fa-burger',
        title: 'Koleksi Rasa Pilihan',
        text: 'Perpaduan sajian gurih, manis, dan segar yang disusun dengan sentuhan visual yang lembut.',
        accent: 'from-orange-500 to-amber-400',
    },
    {
        icon: 'fa-mug-hot',
        title: 'Aroma yang Mengundang',
        text: 'Bayangan uap kopi, kilau minuman dingin, dan gerak halus yang memberi kesan hangat.',
        accent: 'from-emerald-500 to-teal-400',
    },
    {
        icon: 'fa-seedling',
        title: 'Tampilan Premium',
        text: 'Warna lembut, komposisi rapi, dan detail halus untuk menonjolkan karakter makanan dan minuman.',
        accent: 'from-pink-500 to-rose-400',
    },
];

const menuCards = [
    {
        icon: 'fa-bowl-food',
        title: 'Nasi Salmon Citrus',
        subtitle: 'Lembut, segar, dan beraroma ringan',
        price: 'Rp28.000',
        tone: 'from-orange-500 to-red-400',
    },
    {
        icon: 'fa-mug-hot',
        title: 'Iced Latte Vanilla',
        subtitle: 'Dingin, creamy, dan menenangkan',
        price: 'Rp18.000',
        tone: 'from-sky-500 to-cyan-400',
    },
    {
        icon: 'fa-ice-cream',
        title: 'Matcha Sundae',
        subtitle: 'Segar, lembut, dan manis pas',
        price: 'Rp22.000',
        tone: 'from-emerald-500 to-lime-400',
    },
];

const paymentBadges = ['Transfer', 'VA', 'E-Wallet', 'Cash'];

const floatingStickers = [
    { icon: 'fa-cookie-bite', label: 'Cookie', left: '6%', top: '12%', delay: '0s' },
    { icon: 'fa-burger', label: 'Burger', left: '18%', top: '72%', delay: '0.8s' },
    { icon: 'fa-mug-hot', label: 'Coffee', left: '84%', top: '14%', delay: '1.2s' },
    { icon: 'fa-ice-cream', label: 'Dessert', left: '88%', top: '66%', delay: '1.8s' },
    { icon: 'fa-bowl-food', label: 'Bowl', left: '42%', top: '8%', delay: '0.5s' },
];

const confettiDots = [
    { size: '10px', left: '12%', top: '18%', color: 'bg-orange-300', delay: '0s' },
    { size: '8px', left: '26%', top: '28%', color: 'bg-rose-300', delay: '0.9s' },
    { size: '12px', left: '78%', top: '20%', color: 'bg-amber-300', delay: '1.2s' },
    { size: '9px', left: '84%', top: '36%', color: 'bg-pink-300', delay: '1.5s' },
    { size: '7px', left: '64%', top: '10%', color: 'bg-emerald-300', delay: '0.4s' },
    { size: '11px', left: '9%', top: '60%', color: 'bg-cyan-300', delay: '1.1s' },
];

function FloatingCard({ item, active, index }) {
    return (
        <div
            className={`absolute left-0 right-0 rounded-3xl border border-white/70 bg-white/95 p-4 shadow-xl backdrop-blur ${active ? 'scale-100' : 'scale-95 opacity-70'}`}
            style={{
                top: `${index * 92 + 18}px`,
                transform: `translateX(${active ? 0 : index % 2 === 0 ? '-8px' : '8px'}) translateY(${active ? 0 : index === 1 ? '6px' : '-6px'})`,
                transitionDelay: `${index * 80}ms`,
            }}
        >
            <div className="flex items-center gap-3">
                <div className={`flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br ${item.tone} text-white shadow-lg`}>
                    <i className={`fas ${item.icon}`}></i>
                </div>
                <div className="min-w-0 flex-1">
                    <p className="truncate text-sm font-extrabold text-slate-900">{item.title}</p>
                    <p className="truncate text-xs text-slate-500">{item.subtitle}</p>
                </div>
                <div className="text-right">
                    <p className="text-xs font-semibold text-slate-400">Harga</p>
                    <p className="text-sm font-black text-orange-600">{item.price}</p>
                </div>
            </div>
        </div>
    );
}

function FoodMascot({ pointer }) {
    return (
        <div
            className="relative mx-auto w-full max-w-[360px] rounded-[2rem] border border-white/70 bg-gradient-to-br from-white via-orange-50 to-rose-50 p-6 shadow-[0_24px_80px_rgba(15,23,42,.12)] backdrop-blur-sm"
            style={{ transform: `translate3d(${pointer.x * 10}px, ${pointer.y * 8}px, 0) rotate(${pointer.x * 1.5}deg)` }}
        >
            <div className="absolute -left-8 top-10 h-20 w-20 rounded-full bg-orange-200/60 blur-2xl"></div>
            <div className="absolute -right-6 bottom-10 h-24 w-24 rounded-full bg-pink-200/60 blur-2xl"></div>

            <div className="float-food relative mx-auto flex h-52 w-52 items-center justify-center rounded-full bg-gradient-to-b from-amber-100 via-orange-100 to-pink-100 shadow-inner shadow-orange-200/70">
                <div className="absolute bottom-6 h-20 w-40 rounded-full bg-amber-200/80 blur-2xl"></div>
                <div className="relative flex h-36 w-36 items-center justify-center rounded-full bg-gradient-to-b from-white to-orange-50 shadow-lg">
                    <div className="absolute -top-4 flex gap-2 text-3xl text-orange-500">
                        <i className="fas fa-pepper-hot"></i>
                        <i className="fas fa-ice-cream"></i>
                    </div>
                    <div className="relative flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-b from-amber-200 to-orange-200 shadow-inner">
                        <div className="absolute top-5 left-6 h-3 w-3 rounded-full bg-slate-800"></div>
                        <div className="absolute top-5 right-6 h-3 w-3 rounded-full bg-slate-800"></div>
                        <div className="absolute top-11 h-3 w-8 rounded-b-full border-b-4 border-slate-800"></div>
                        <div className="absolute bottom-4 flex gap-2 text-xl text-rose-500">
                            <i className="fas fa-heart"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div className="mt-5 grid grid-cols-2 gap-3">
                <div className="rounded-2xl bg-white px-4 py-3 shadow-sm">
                    <p className="text-[11px] font-black uppercase tracking-[0.22em] text-orange-500">Mascot</p>
                    <p className="mt-1 text-sm font-bold text-slate-800">Chef Snacky</p>
                </div>
                <div className="rounded-2xl bg-white px-4 py-3 shadow-sm">
                    <p className="text-[11px] font-black uppercase tracking-[0.22em] text-orange-500">Mood</p>
                    <p className="mt-1 text-sm font-bold text-slate-800">Happy & Hungry</p>
                </div>
            </div>
        </div>
    );
}

export default function WelcomeDashboard() {
    const [activeIndex, setActiveIndex] = useState(0);
    const [pointer, setPointer] = useState({ x: 0, y: 0 });

    useEffect(() => {
        const timer = window.setInterval(() => {
            setActiveIndex((current) => (current + 1) % menuCards.length);
        }, 2800);

        return () => window.clearInterval(timer);
    }, []);

    const handleHeroMove = (event) => {
        const bounds = event.currentTarget.getBoundingClientRect();
        const x = ((event.clientX - bounds.left) / bounds.width - 0.5) * 2;
        const y = ((event.clientY - bounds.top) / bounds.height - 0.5) * 2;
        setPointer({ x, y });
    };

    const handleHeroLeave = () => setPointer({ x: 0, y: 0 });

    const routes = window.__WELCOME_ROUTES__ || {};

    return (
        <div className="min-h-screen bg-[#fff8f1] text-slate-900">
            <style>{`
                @keyframes floatFood {
                    0%, 100% { transform: translateY(0px) rotate(0deg); }
                    50% { transform: translateY(-12px) rotate(1.5deg); }
                }
                @keyframes driftBubble {
                    0% { transform: translate3d(0, 0, 0) scale(1); opacity: .55; }
                    50% { transform: translate3d(12px, -18px, 0) scale(1.08); opacity: .8; }
                    100% { transform: translate3d(0, 0, 0) scale(1); opacity: .55; }
                }
                @keyframes steam {
                    0% { transform: translateY(12px) scale(.92); opacity: .2; }
                    50% { transform: translateY(-8px) scale(1); opacity: .75; }
                    100% { transform: translateY(12px) scale(.92); opacity: .2; }
                }
                @keyframes shimmer {
                    0% { background-position: 0% 50%; }
                    100% { background-position: 100% 50%; }
                }
                .float-food { animation: floatFood 5s ease-in-out infinite; }
                .drift-bubble { animation: driftBubble 6s ease-in-out infinite; }
                .steam-line { animation: steam 2.2s ease-in-out infinite; }
                .shimmer-text {
                    background-size: 200% 200%;
                    animation: shimmer 5s linear infinite;
                }
            `}</style>

            <nav className="sticky top-0 z-50 border-b border-orange-100/90 bg-white/85 backdrop-blur-xl">
                <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                    <a href="/" className="flex items-center gap-3">
                        <span className="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 via-rose-500 to-amber-400 text-white shadow-lg shadow-orange-200">
                            <i className="fas fa-burger"></i>
                        </span>
                        <span>
                            <span className="block text-[11px] font-extrabold uppercase tracking-[0.32em] text-orange-500">Fast Order</span>
                            <span className="block text-lg font-black leading-none text-slate-900">Gateway Makanan Minuman</span>
                        </span>
                    </a>

                    <div className="hidden items-center gap-8 md:flex">
                        <a href="#fitur" className="text-sm font-semibold text-slate-600 transition hover:text-orange-600">Fitur</a>
                        <a href="#cara-kerja" className="text-sm font-semibold text-slate-600 transition hover:text-orange-600">Cara Kerja</a>
                        <a href="#menu" className="text-sm font-semibold text-slate-600 transition hover:text-orange-600">Menu</a>
                    </div>

                    <div className="flex items-center gap-2 sm:gap-3">
                        <a href={routes.cart || '#'} className="rounded-2xl border border-orange-200 bg-white px-3 py-2 text-sm font-bold text-orange-700 transition hover:-translate-y-0.5 hover:bg-orange-50 sm:px-4">
                            <i className="fas fa-cart-shopping mr-1"></i> Keranjang
                        </a>
                        <a href={routes.login || '#'} className="rounded-2xl bg-slate-900 px-3 py-2 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-slate-700 sm:px-4">
                            Login Vendor
                        </a>
                    </div>
                </div>
            </nav>

            <main className="relative overflow-hidden">
                <section
                    className="relative bg-gradient-to-br from-amber-50 via-orange-50 to-rose-100 py-16 lg:py-24"
                    onMouseMove={handleHeroMove}
                    onMouseLeave={handleHeroLeave}
                >
                    <div className="pointer-events-none absolute -left-20 top-14 h-64 w-64 rounded-full bg-orange-300/30 blur-3xl"></div>
                    <div className="pointer-events-none absolute -right-16 top-24 h-72 w-72 rounded-full bg-pink-300/30 blur-3xl"></div>
                    <div className="pointer-events-none absolute bottom-0 left-1/2 h-44 w-44 -translate-x-1/2 rounded-full bg-amber-200/40 blur-3xl"></div>
                    {floatingStickers.map((sticker) => (
                        <div
                            key={sticker.label}
                            className="pointer-events-none absolute hidden items-center gap-2 rounded-full border border-white/70 bg-white/80 px-3 py-2 text-xs font-black text-slate-700 shadow-lg shadow-orange-100/60 backdrop-blur-sm lg:flex"
                            style={{ left: sticker.left, top: sticker.top, animationDelay: sticker.delay, transform: `translate3d(${pointer.x * 6}px, ${pointer.y * 6}px, 0)` }}
                        >
                            <span className="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-rose-500 text-white shadow-sm">
                                <i className={`fas ${sticker.icon}`}></i>
                            </span>
                            {sticker.label}
                        </div>
                    ))}
                    {confettiDots.map((dot, index) => (
                        <span
                            key={`${dot.left}-${index}`}
                            className={`pointer-events-none absolute hidden rounded-full opacity-70 lg:block ${dot.color}`}
                            style={{
                                left: dot.left,
                                top: dot.top,
                                width: dot.size,
                                height: dot.size,
                                animation: `driftBubble 5.5s ease-in-out infinite`,
                                animationDelay: dot.delay,
                            }}
                        />
                    ))}
                    <div
                        className="pointer-events-none absolute left-8 top-20 h-16 w-16 rounded-full bg-white/70 shadow-lg shadow-orange-100/50 backdrop-blur-sm"
                        style={{ transform: `translate3d(${pointer.x * 20}px, ${pointer.y * 16}px, 0)` }}
                    >
                        <div className="flex h-full w-full items-center justify-center text-orange-500">
                            <i className="fas fa-cookie-bite text-2xl"></i>
                        </div>
                    </div>
                    <div
                        className="pointer-events-none absolute right-10 top-16 h-14 w-14 rounded-full bg-white/70 shadow-lg shadow-pink-100/50 backdrop-blur-sm"
                        style={{ transform: `translate3d(${pointer.x * -24}px, ${pointer.y * 14}px, 0)` }}
                    >
                        <div className="flex h-full w-full items-center justify-center text-pink-500">
                            <i className="fas fa-bubble-tea text-xl"></i>
                        </div>
                    </div>

                    <div className="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
                        <div className="relative z-10">
                            <span className="inline-flex items-center rounded-full border border-orange-200 bg-white px-4 py-1 text-[11px] font-black uppercase tracking-[0.3em] text-orange-700 shadow-sm">
                                Sajian pilihan untuk momen istimewa
                            </span>

                            <h1 className="mt-5 max-w-2xl text-4xl font-black leading-[1.28] text-slate-950 sm:text-5xl sm:leading-[1.22] lg:text-7xl lg:leading-[1.16]">
                                <span className="block pb-2">Hangat,</span>
                                <span className="shimmer-text block pb-3 bg-gradient-to-r from-orange-600 via-rose-500 to-amber-500 bg-clip-text text-transparent">Segar, dan Menggoda</span>
                            </h1>

                            <p className="mt-6 max-w-xl text-base leading-7 text-slate-600 sm:text-lg">
                                Aroma kopi yang halus, minuman yang sejuk, dan sajian yang tertata rapi memberi kesan hangat, modern, dan menyenangkan.
                            </p>

                            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
                                <a href={routes.customerDashboard || '#'} className="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-orange-600 to-rose-500 px-6 py-3.5 text-base font-black text-white shadow-lg shadow-orange-200 transition hover:-translate-y-0.5 hover:shadow-orange-300">
                                    <i className="fas fa-utensils mr-2"></i> Jelajahi Rasa
                                </a>
                                <a href={routes.cart || '#'} className="inline-flex items-center justify-center rounded-2xl border-2 border-slate-300 bg-white px-6 py-3.5 text-base font-black text-slate-700 transition hover:-translate-y-0.5 hover:border-slate-400 hover:bg-slate-50">
                                    <i className="fas fa-cart-plus mr-2"></i> Lihat Pilihan
                                </a>
                            </div>

                            <div className="mt-10 grid max-w-xl grid-cols-3 gap-4">
                                {heroStats.map((item) => (
                                    <div key={item.label} className="rounded-3xl border border-white/70 bg-white/90 p-4 shadow-lg shadow-orange-100/60 backdrop-blur-sm">
                                        <div className="mb-2 flex h-10 w-10 items-center justify-center rounded-2xl bg-orange-50 text-orange-600">
                                            <i className={`fas ${item.icon}`}></i>
                                        </div>
                                        <p className="text-2xl font-black text-orange-600">{item.value}</p>
                                        <p className="text-sm font-medium text-slate-600">{item.label}</p>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="relative mx-auto w-full max-w-xl">
                            <div className="float-food relative overflow-hidden rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-[0_30px_90px_rgba(15,23,42,.12)] backdrop-blur-sm">
                                <div className="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-200/50 blur-2xl"></div>
                                <div className="absolute -left-10 bottom-4 h-28 w-28 rounded-full bg-rose-200/50 blur-2xl"></div>

                                <div className="mb-4 flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-black uppercase tracking-[0.25em] text-orange-500">Sorotan rasa</p>
                                        <h2 className="mt-1 text-2xl font-black text-slate-900">Snack & Drink Mood Board</h2>
                                    </div>
                                    <span className="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">Siap Sajikan</span>
                                </div>

                                <div className="relative h-[330px]">
                                    {menuCards.map((item, index) => (
                                        <FloatingCard key={item.title} item={item} active={index === activeIndex} index={index} />
                                    ))}
                                </div>

                                <div className="mt-5 rounded-2xl bg-gradient-to-r from-slate-900 to-slate-800 p-4 text-white shadow-lg">
                                    <div className="mb-3 flex items-center justify-between text-sm text-slate-200">
                                        <span>Fresh serving vibes</span>
                                        <span className="flex items-center gap-2 text-emerald-300"><span className="h-2 w-2 rounded-full bg-emerald-300"></span> Fresh</span>
                                    </div>
                                    <div className="grid grid-cols-4 gap-2 text-center text-xs font-bold">
                                        {paymentBadges.map((badge) => (
                                            <span key={badge} className="rounded-xl bg-white/10 px-3 py-2 backdrop-blur">{badge}</span>
                                        ))}
                                    </div>
                                </div>

                                <div className="mt-4 grid grid-cols-3 gap-2 text-center text-[11px] font-black uppercase tracking-[0.22em] text-slate-500">
                                    <span className="rounded-full bg-orange-50 px-3 py-2 text-orange-600">Creamy</span>
                                    <span className="rounded-full bg-rose-50 px-3 py-2 text-rose-600">Fresh</span>
                                    <span className="rounded-full bg-emerald-50 px-3 py-2 text-emerald-600">Warm</span>
                                </div>
                            </div>

                            <div className="drift-bubble absolute -bottom-3 right-8 rounded-3xl border border-white/70 bg-white/90 px-4 py-3 shadow-lg shadow-pink-100/70 backdrop-blur-sm" style={{ animationDelay: '0.4s' }}>
                                <p className="text-xs font-bold uppercase tracking-[0.2em] text-pink-500">Signature drink</p>
                                <p className="mt-1 text-sm font-black text-slate-900">Brown Sugar Boba</p>
                            </div>
                        </div>
                    </div>

                    <div className="mx-auto mt-12 max-w-7xl px-4 sm:px-6 lg:px-8">
                        <FoodMascot pointer={pointer} />
                    </div>
                </section>

                <section id="fitur" className="bg-white py-20">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="mx-auto mb-12 max-w-2xl text-center">
                            <span className="inline-flex items-center rounded-full bg-orange-50 px-4 py-1 text-xs font-black uppercase tracking-[0.28em] text-orange-700">Sorotan sajian</span>
                            <h2 className="mt-4 text-3xl font-black text-slate-950 sm:text-4xl">Rapi, hangat, dan menggugah selera</h2>
                            <p className="mt-3 text-lg text-slate-600">Setiap elemen dirancang untuk menonjolkan karakter rasa, aroma, dan kesegaran.</p>
                        </div>

                        <div className="grid gap-6 md:grid-cols-3">
                            {featureCards.map((card) => (
                                <div key={card.title} className="group rounded-[1.75rem] border border-orange-100 bg-gradient-to-br from-white to-orange-50 p-6 shadow-[0_20px_60px_rgba(15,23,42,.06)] transition duration-300 hover:-translate-y-2 hover:rotate-[-1deg] hover:shadow-[0_24px_80px_rgba(234,88,12,.14)]">
                                    <div className={`mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br ${card.accent} text-white shadow-lg`}>
                                        <i className={`fas ${card.icon} text-xl`}></i>
                                    </div>
                                    <h3 className="text-xl font-black text-slate-900">{card.title}</h3>
                                    <p className="mt-2 text-sm leading-7 text-slate-600">{card.text}</p>
                                    <div className="mt-4 flex items-center gap-2 opacity-70 transition group-hover:opacity-100">
                                        <span className="h-2.5 w-2.5 rounded-full bg-orange-400"></span>
                                        <span className="h-2.5 w-2.5 rounded-full bg-rose-400"></span>
                                        <span className="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                <section id="cara-kerja" className="bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 py-20 text-white">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="mb-12 text-center">
                            <span className="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-1 text-xs font-black uppercase tracking-[0.28em] text-orange-300">Urutan sajian</span>
                            <h2 className="mt-4 text-3xl font-black sm:text-4xl">Langkah yang terasa hangat dan natural</h2>
                        </div>

                        <div className="grid gap-6 md:grid-cols-3">
                            {[
                                { step: '01', title: 'Pilih sajian', text: 'Telusuri hidangan gurih, manis, dan minuman favorit yang paling menarik selera.' },
                                { step: '02', title: 'Racik pesanan', text: 'Gabungkan pilihan makanan dan minuman dengan komposisi rasa yang pas.' },
                                { step: '03', title: 'Nikmati momen', text: 'Sajikan pilihan terbaik untuk suasana yang lebih hangat dan berkesan.' },
                            ].map((item) => (
                                <div key={item.step} className="rounded-[1.75rem] border border-white/10 bg-white/5 p-6 shadow-xl backdrop-blur-sm">
                                    <p className="text-sm font-black tracking-[0.3em] text-orange-300">LANGKAH {item.step}</p>
                                    <h3 className="mt-3 text-2xl font-black">{item.title}</h3>
                                    <p className="mt-2 text-sm leading-7 text-slate-300">{item.text}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                <section id="menu" className="bg-white py-20">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="mb-12 text-center">
                            <span className="inline-flex items-center rounded-full bg-orange-50 px-4 py-1 text-xs font-black uppercase tracking-[0.28em] text-orange-700">Koleksi rasa</span>
                            <h2 className="mt-4 text-3xl font-black text-slate-950 sm:text-4xl">Pilihan makanan dan minuman yang menggoda</h2>
                            <p className="mt-3 text-lg text-slate-600">Setiap kartu menonjolkan aroma, tekstur, dan kesan segar secara elegan.</p>
                        </div>

                        <div className="grid gap-6 md:grid-cols-3">
                            {menuCards.map((item, index) => (
                                <div
                                    key={item.title}
                                    className="group relative overflow-hidden rounded-[1.8rem] border border-orange-100 bg-gradient-to-br from-white via-orange-50 to-rose-50 p-6 shadow-[0_18px_55px_rgba(15,23,42,.08)] transition duration-300 hover:-translate-y-3 hover:rotate-[-1.5deg] hover:shadow-[0_28px_80px_rgba(234,88,12,.16)]"
                                    style={{ transform: `translateY(${index % 2 === 0 ? '0px' : '8px'})` }}
                                >
                                    <div className={`absolute -right-8 -top-8 h-24 w-24 rounded-full bg-gradient-to-br ${item.tone} opacity-20 blur-xl transition group-hover:scale-125`}></div>
                                    <div className="absolute right-5 top-5 flex gap-1 opacity-0 transition group-hover:opacity-100">
                                        <span className="h-2 w-2 rounded-full bg-orange-300"></span>
                                        <span className="h-2 w-2 rounded-full bg-rose-300"></span>
                                        <span className="h-2 w-2 rounded-full bg-amber-300"></span>
                                    </div>
                                    <div className={`mb-5 inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br ${item.tone} text-white shadow-lg transition duration-300 group-hover:rotate-12 group-hover:scale-110`}>
                                        <i className={`fas ${item.icon} text-2xl`}></i>
                                    </div>
                                    <p className="text-xs font-black uppercase tracking-[0.25em] text-orange-500">Trending menu</p>
                                    <h3 className="mt-2 text-2xl font-black text-slate-950">{item.title}</h3>
                                    <p className="mt-2 text-sm leading-7 text-slate-600">{item.subtitle}</p>
                                    <div className="mt-4 h-2 w-full overflow-hidden rounded-full bg-white/80">
                                        <div className={`h-full w-2/3 rounded-full bg-gradient-to-r ${item.tone} transition-all duration-500 group-hover:w-full`}></div>
                                    </div>
                                    <div className="mt-6 flex items-center justify-between">
                                        <span className="rounded-full bg-slate-900 px-3 py-1 text-xs font-bold text-white">Signature taste</span>
                                        <span className="text-xl font-black text-orange-600">{item.price}</span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                <section className="bg-[#fff8f1] py-20">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="rounded-[2rem] bg-gradient-to-r from-orange-600 via-rose-500 to-pink-500 p-8 text-white shadow-[0_25px_70px_rgba(234,88,12,.25)] md:p-10">
                            <div className="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                                <div className="max-w-2xl">
                                    <span className="inline-flex items-center rounded-full bg-white/15 px-4 py-1 text-xs font-black uppercase tracking-[0.28em] text-white/90">Siap disajikan</span>
                                    <h2 className="mt-4 text-3xl font-black sm:text-4xl">Tampilan yang menonjolkan rasa dan kesan premium.</h2>
                                    <p className="mt-3 text-white/90">Kalau mau, saya bisa lanjutkan ke versi yang lebih editorial: tone lebih mewah, lebih minimal, atau lebih playful dengan nuansa café dan dessert bar.</p>
                                </div>
                                <div className="flex flex-col gap-3 sm:flex-row">
                                    <a href={routes.customerDashboard || '#'} className="rounded-2xl bg-white px-6 py-3.5 text-center font-black text-orange-700 transition hover:-translate-y-0.5 hover:bg-orange-50">
                                        Jelajahi Rasa
                                    </a>
                                    <a href={routes.login || '#'} className="rounded-2xl border-2 border-white px-6 py-3.5 text-center font-black text-white transition hover:-translate-y-0.5 hover:bg-white/10">
                                        Login Vendor
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    );
}