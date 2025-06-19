// Javascript Document
(()=>{
  return{
    data(){
      return {
        root: appui.plugins['appui-database'] + '/',
        data: {
          name: '',
          host_id: this.source.host_id,
          engine: this.source.engine,
          charset: this.source.charset || '',
          collation: this.source.collation || ''
        }
      }
    }
  }
})();