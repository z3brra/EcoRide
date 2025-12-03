export interface FixedPref {
    animals: boolean
    smoke: boolean
}

export interface CustomPref {
    uuid: string
    label: string
    createdAt: string
    updatedAt?: string
}

export interface AggregatedPref {
    animals: boolean
    smoke: boolean
    customPreferences: CustomPref[]
}

export interface CreateCustomPref {
    label: string
}


export interface UpdateFixedPref {
    animals: boolean
    smoke: boolean
}

export interface UpdateCustomPref {
    uuid: string
    label: string
}

export interface UpdateAggregatedPref {
    fixedPref?: UpdateFixedPref
    customPref?: UpdateCustomPref[]
}