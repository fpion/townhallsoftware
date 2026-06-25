export type SessionStatus = 'planned' | 'open' | 'closed'
export type AttendanceStatus = 'present' | 'absent_excuse' | 'absent' | 'procuration'
export type DeliberationStatus = 'pending' | 'adopted' | 'rejected' | 'withdrawn'

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
  presentCount: number
  attendances: AttendanceView[]
  deliberations: DeliberationView[]
}

export interface ActionState {
  error?: string
}
