/**
 * Script modificado do componente registration-print para incluir método downloadPDF
 */

app.component('registration-print', {
    template: $TEMPLATES['registration-print'],
    
    // define os eventos que este componente emite
    emits: ['namesDefined'],

    props: {
        registration: {
            type: Entity,
            required: true,
        },
    },
    
    data() {
        return {
            loading: false,
        }
    },
    
    methods: {
        print() {
            const self = this;
            self.loading = true;
            var iframe = this.$refs.printIframe;

            iframe.addEventListener("load", function(e) {      
                setTimeout(() => {
                    self.loading = false;
                }, 1000);
            });

            iframe.src = Utils.createUrl('registration', 'registrationPrint', [this.registration.id]);
        },
        
        downloadPDF() {
            const url = Utils.createUrl('registration', 'downloadPdf', [this.registration.id]);
            window.open(url, '_blank');
        }
    },
});