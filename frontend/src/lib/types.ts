export type SessionStatus = 'planned' | 'open' | 'closed'
export type AttendanceStatus = 'present' | 'absent_excuse' | 'absent' | 'procuration'
export type DeliberationStatus = 'pending' | 'adopted' | 'rejected' | 'withdrawn'
export type CouncilorRole = 'maire' | 'maire_adjoint' | 'conseiller_delegue' | 'conseiller'

export interface AttendanceView {
  councilorId: string
  status: AttendanceStatus
  statusLabel: string
  proxyHolderId: string | null
}

export interface DeliberationVote {
  pour: number
  contre: number
  abstention: number
}

export interface DeliberationView {
  id: string
  number: string
  title: string
  description: string
  status: DeliberationStatus
  statusLabel: string
  vote: DeliberationVote | null
}

export interface CouncilSessionView {
  id: string
  townHallCode: string
  date: string
  orderOfBusiness: string
  status: SessionStatus
  statusLabel: string
  sessionType: string
  exceptional: boolean
  presentCount: number
  attendances: AttendanceView[]
  deliberations: DeliberationView[]
}

export interface TownHallView {
  code: string
  name: string
  street: string
  city: string
  postalCode: string
  population: number
  maxCouncilors: number
  maxAdjoints: number
}

export interface CouncilorView {
  id: string
  firstName: string
  lastName: string
  email: string
  role: CouncilorRole
  roleLabel: string
  active: boolean
}

export interface ActionState {
  error?: string
}
