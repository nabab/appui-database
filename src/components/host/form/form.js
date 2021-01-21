// Javascript Document
(()=>{
  return {
    props: ['source'],
    data(){
      let data = {
        engine: this.source.engine,
        host: '',
        name: ''
      };
      if (this.source.engine !== 'sqlite') {
        data.username = '';
        data.password = '';
      }
      return {
        root: appui.plugins['appui-database'],
        checked: 0,
        formData: data
      };
    },
    methods: {
      changeHost(){
        if (this.formData.host && !this.formData.name) {
          this.formData.name = this.formData.host;
        }
      },
      success(){},
      checkConnection(){
        if (this.checked) {
          this.checked = 0;
        }

        if (this.formData.password && this.formData.password && this.formData.host) {
          this.post(this.root + '/actions/host/connect', this.formData, d => {
            if (d && d.success) {
              this.checked = 1;
              appui.success();
            }
          })
        }
      }
    }
  };
})();