// Javascript Document
(()=> {
  return {
    data(){
      return {
        link:  appui.plugins['appui-database'] + '/tabs/' + this.source.engine + '/' + this.source.id_host + '/' + this.source.name + '/home'
      };
    },
    methods: {
      fdatetime: bbn.fn.fdatetime
    }
  };
})();
