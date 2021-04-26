// Javascript Document
(()=>{
  return {
    props: ['source'],
    data(){
      let data = {
        engine: this.source.engine,
        host: this.source.host,
        db: this.source.name
      };
      return {
        root: appui.plugins['appui-database'],
        checked: 0,
        formData: data
      };
    },
    methods: {
      success(){},
    }
  };
})();