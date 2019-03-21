<template>
    <b-card header="Filters">
        <b-form-group>
            <template slot="label">
                <b-form-checkbox v-model="allSelectedAll"
                                :indeterminate="indeterminateAll"
                                @change="toggleAll"
                >
                    {{ allSelectedAll ? 'Select all' : 'Unselect all' }}
                </b-form-checkbox>
            </template>
        </b-form-group>  
        <hr>
        <b-form-group>
            <template slot="label">
                <b-form-checkbox v-model="allSelectedFootball"
                                :indeterminate="indeterminateFootball"
                                aria-controls="sportsFootball"
                                @change="toggleFootball"
                >
                    {{ allSelectedFootball ? 'Football' : 'Football' }}
                </b-form-checkbox>
            </template>
            <b-form-checkbox-group
                                stacked
                                v-model="selectedFootball"
                                name="sportsFootball"
                                :options="sportsFootball"
                                class="ml-4"
            >
            </b-form-checkbox-group>
        </b-form-group>  
        <hr>
        <b-form-group>
            <template slot="label">
                <b-form-checkbox v-model="allSelectedBasketball"
                                :indeterminate="indeterminateBasketball"
                                aria-controls="sportsBasketball"
                                @change="toggleBasketball"
                >
                    {{ allSelectedBasketball ? 'Basketball' : 'Basketball' }}
                </b-form-checkbox>
            </template>
            <b-form-checkbox-group
                                stacked
                                v-model="selectedBasketball"
                                name="sportsBasketball"
                                :options="sportsBasketball"
                                class="ml-4"
            >
            </b-form-checkbox-group>
        </b-form-group> 
        <hr>
        <b-form-group>
            <template slot="label">
                <b-form-checkbox v-model="allSelectedIceHockey"
                                :indeterminate="indeterminateIceHockey"
                                aria-controls="sportsIceHockey"
                                @change="toggleIceHockey"
                >
                    {{ allSelectedIceHockey ? 'Ice Hockey' : 'Ice Hockey' }}
                </b-form-checkbox>
            </template>
            <b-form-checkbox-group
                                stacked
                                v-model="selectedIceHockey"
                                value="selectedIceHockey"
                                name="sportsIceHockey"
                                :options="sportsIceHockey"
                                class="ml-4"
            >
            </b-form-checkbox-group>
        </b-form-group> 
    </b-card>
</template>

<script>
export default {
    data () {
        return {
            sportsFootball: ['Premier League', 'La Liga', 'Bundesliga'],
            sportsBasketball: ['NBA'],
            sportsIceHockey: ['NHL'],
            selectedFootball: ['Premier League', 'La Liga', 'Bundesliga'],
            selectedBasketball: ['NBA'],
            selectedIceHockey: ['NHL'],
            allSelectedFootball: true,
            indeterminateFootball: false,
            allSelectedBasketball: true,
            indeterminateBasketball: false,
            allSelectedIceHockey: true,
            indeterminateIceHockey: false,
            allSelectedAll: true,
            indeterminateAll: false,
        }
    },
    methods: {
        toggleFootball (checked) {
            this.selectedFootball = checked ? this.sportsFootball.slice() : []
            this.$emit('filterChanged', this.selectedFootball, this.selectedBasketball, this.selectedIceHockey)
        },
        toggleBasketball (checked) {
            this.selectedBasketball = checked ? this.sportsBasketball.slice() : []
            this.$emit('filterChanged', this.selectedFootball, this.selectedBasketball, this.selectedIceHockey)
        },
        toggleIceHockey (checked) {
            this.selectedIceHockey = checked ? this.sportsIceHockey.slice() : []
            this.$emit('filterChanged', this.selectedFootball, this.selectedBasketball, this.selectedIceHockey)
        },
        toggleAll (checked) {
            this.selectedIceHockey = checked ? this.sportsIceHockey.slice() : [],
            this.selectedBasketball = checked ? this.sportsBasketball.slice() : [],
            this.selectedFootball = checked ? this.sportsFootball.slice() : []
            this.$emit('filterChanged', this.selectedFootball, this.selectedBasketball, this.selectedIceHockey)
        }
    },
    watch: {
        selectedFootball (newVal, oldVal) {
            if (newVal.length === 0) {
                this.indeterminateFootball = false
                this.allSelectedFootball = false

                if(!this.allSelectedBasketball && !this.allSelectedIceHockey){
                    this.indeterminateAll = false
                    this.allSelectedAll = false
                }else{
                    this.indeterminateAll = true
                    this.allSelectedAll = false
                }
            } else if (newVal.length === this.sportsFootball.length) {
                this.indeterminateFootball = false
                this.allSelectedFootball = true

                if(this.allSelectedBasketball && this.allSelectedIceHockey)
                {
                    this.indeterminateAll = false
                    this.allSelectedAll = true
                }
            } else {
                this.indeterminateFootball = true
                this.allSelectedFootball = false

                this.indeterminateAll = true
                this.allSelectedAll = false
            }
            this.$emit('filterChanged', this.selectedFootball, this.selectedBasketball, this.selectedIceHockey)
        },
        selectedBasketball (newVal, oldVal) {
            if (newVal.length === 0) {
                this.indeterminateBasketball = false
                this.allSelectedBasketball = false

                if(!this.allSelectedFootball && !this.allSelectedIceHockey){
                    this.indeterminateAll = false
                    this.allSelectedAll = false
                }else{
                    this.indeterminateAll = true
                    this.allSelectedAll = false
                }
            } else if (newVal.length === this.sportsBasketball.length) {
                this.indeterminateBasketball = false
                this.allSelectedBasketball = true

                if(this.allSelectedFootball && this.allSelectedIceHockey)
                {
                    this.indeterminateAll = false
                    this.allSelectedAll = true
                }
            } else {
                this.indeterminateBasketball = true
                this.allSelectedBasketball = false

                this.indeterminateAll = true
                this.allSelectedAll = false
            }
            this.$emit('filterChanged', this.selectedFootball, this.selectedBasketball, this.selectedIceHockey)
        },
        selectedIceHockey (newVal, oldVal) {
            if (newVal.length === 0) {
                this.indeterminateIceHockey = false
                this.allSelectedIceHockey = false

                if(!this.allSelectedFootball && !this.allSelectedBasketball){
                    this.indeterminateAll = false
                    this.allSelectedAll = false
                }else{
                    this.indeterminateAll = true
                    this.allSelectedAll = false
                }
            } else if (newVal.length === this.sportsIceHockey.length) {
                this.indeterminateIceHockey = false
                this.allSelectedIceHockey = true

                if(this.allSelectedBasketball && this.allSelectedFootball)
                {
                    this.indeterminateAll = false
                    this.allSelectedAll = true
                }
            } else {
                this.indeterminateIceHockey = true
                this.allSelectedIceHockey = false

                this.indeterminateAll = true
                this.allSelectedAll = false
            }
            this.$emit('filterChanged', this.selectedFootball, this.selectedBasketball, this.selectedIceHockey)
        },
        selectedAll (newVal, oldVal) {
            if (newVal.length === 0) {
                this.indeterminateAll = false
                this.allSelectedAll = false
            } else if (newVal.length === this.sportsIceHockey.length+this.sportsBasketball.length+this.sportsIceHockey.length) {
                this.indeterminateAll = false
                this.allSelectedAll = true
            } else {
                this.indeterminateAll = true
                this.allSelectedAll = false
            }
            this.$emit('filterChanged', this.selectedFootball, this.selectedBasketball, this.selectedIceHockey)
        }
    }
}
</script>
