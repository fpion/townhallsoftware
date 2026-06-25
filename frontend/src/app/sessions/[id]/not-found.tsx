import Link from 'next/link'

export default function SessionNotFound() {
  return (
    <div className="text-center py-20">
      <h2 className="text-2xl font-semibold mb-4">Séance introuvable</h2>
      <p className="text-neutral-600 dark:text-neutral-400 mb-8">
        Cette séance n&apos;existe pas ou a été supprimée.
      </p>
      <Link href="/sessions/new" className="text-blue-600 hover:underline">
        Créer une nouvelle séance
      </Link>
    </div>
  )
}
