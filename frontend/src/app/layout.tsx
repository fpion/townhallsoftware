import type { Metadata } from 'next'
import { Geist, Geist_Mono } from 'next/font/google'
import './globals.css'

const geistSans = Geist({ variable: '--font-geist-sans', subsets: ['latin'] })
const geistMono = Geist_Mono({ variable: '--font-geist-mono', subsets: ['latin'] })

export const metadata: Metadata = {
  title: { default: 'TownHall — Conseil Municipal', template: '%s | TownHall' },
  description: 'Gestion des séances du conseil municipal',
}

const navLinks = [
  { href: '/town-halls', label: 'Mairies' },
  { href: '/councilors', label: 'Conseillers' },
  { href: '/sessions/new', label: 'Nouvelle séance' },
]

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="fr" className={`${geistSans.variable} ${geistMono.variable} h-full antialiased`}>
      <body className="min-h-full flex flex-col bg-background text-foreground">
        <header className="border-b border-neutral-200 dark:border-neutral-800 px-6 py-3">
          <nav className="container mx-auto max-w-4xl flex items-center gap-6">
            <a href="/" className="text-base font-semibold tracking-tight hover:opacity-80 mr-4">
              Conseil Municipal
            </a>
            {navLinks.map(({ href, label }) => (
              <a
                key={href}
                href={href}
                className="text-sm text-neutral-600 dark:text-neutral-400 hover:text-foreground transition-colors"
              >
                {label}
              </a>
            ))}
          </nav>
        </header>
        <main className="flex-1 container mx-auto max-w-4xl px-6 py-8">
          {children}
        </main>
      </body>
    </html>
  )
}
