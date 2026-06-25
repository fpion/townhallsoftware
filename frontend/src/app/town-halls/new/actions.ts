'use server'

import { redirect } from 'next/navigation'
import { createTownHall } from '@/lib/api'
import type { ActionState } from '@/lib/types'

export async function createTownHallAction(
  _prev: ActionState,
  formData: FormData,
): Promise<ActionState> {
  const code = formData.get('code') as string
  const name = formData.get('name') as string
  const street = formData.get('street') as string
  const city = formData.get('city') as string
  const postalCode = formData.get('postalCode') as string
  const populationRaw = formData.get('population') as string

  if (!code || !name || !street || !city || !postalCode || !populationRaw) {
    return { error: 'Tous les champs sont obligatoires.' }
  }

  const population = parseInt(populationRaw, 10)
  if (isNaN(population) || population < 0) {
    return { error: 'La population doit être un nombre positif.' }
  }

  try {
    await createTownHall({ code, name, street, city, postalCode, population })
  } catch (e) {
    return { error: e instanceof Error ? e.message : 'Erreur lors de la création.' }
  }

  redirect('/town-halls')
}
