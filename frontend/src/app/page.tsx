import type { Metadata } from 'next'

export const metadata: Metadata = { title: 'Accueil' }

const sections = [
  {
    href: '/town-halls',
    title: 'Mairies',
    description: 'Créer et consulter les mairies avec leur population et composition légale.',
  },
  {
    href: '/councilors',
    title: 'Conseillers',
    description: 'Gérer les conseillers municipaux et attribuer leurs rôles.',
  },
  {
    href: '/sessions/new',
    title: 'Nouvelle séance',
    description: 'Planifier une séance ordinaire ou exceptionnelle du conseil municipal.',
  },
]

export default function HomePage() {
  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-3xl font-bold tracking-tight">Conseil Municipal</h1>
        <p className="mt-2 text-neutral-500 dark:text-neutral-400">
          Gestion des séances, conseillers et délibérations.
        </p>
      </div>

      <div className="grid gap-4 sm:grid-cols-3">
        {sections.map(({ href, title, description }) => (
          <a
            key={href}
            href={href}
            className="group rounded-xl border border-neutral-200 dark:border-neutral-800 p-5 hover:border-blue-500 dark:hover:border-blue-500 transition-colors"
          >
            <h2 className="font-semibold group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
              {title}
            </h2>
            <p className="mt-1 text-sm text-neutral-500 dark:text-neutral-400">{description}</p>
          </a>
        ))}
      </div>
    </div>
  )
}
