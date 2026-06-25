import type { Metadata } from 'next'
import { redirect } from 'next/navigation'

export const metadata: Metadata = { title: 'Conseillers municipaux' }

export default function CouncilorsPage() {
  redirect('/town-halls')
}
