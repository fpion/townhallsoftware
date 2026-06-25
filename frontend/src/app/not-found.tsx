import Link from 'next/link'

export default function NotFound() {
  return (
    <div className="text-center py-20">
      <h1 className="text-4xl font-bold mb-4">404</h1>
      <p className="text-neutral-600 dark:text-neutral-400 mb-8">Page introuvable.</p>
      <Link href="/sessions/new" className="text-blue-600 hover:underline">
        Créer une séance
      </Link>
    </div>
  )
}
