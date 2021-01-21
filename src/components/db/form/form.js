// Javascript Document
(()=>{
  return{
    data(){
      return {
        root: appui.plugins['appui-database'] + '/',
        data: {
          name: '',
          host_id: this.source.host_id
        }
      }
    }
  }
})();