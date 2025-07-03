// Javascript Document
(()=> {
  return {
    data(){
      return {
        link:  appui.plugins['appui-database'] + '/tabs/' + this.source.engine + '/' + this.source.id_host + '/' + this.source.name + '/home'
      };
    },
    computed: {
      isRealVirtual(){
        return this.source.is_real && this.source.is_virtual;
      },
      isOnlyVirtual(){
        return !this.source.is_real && this.source.is_virtual;
      }
    },
    methods: {
      fdatetime: bbn.fn.fdatetime
    }
  };
})();
