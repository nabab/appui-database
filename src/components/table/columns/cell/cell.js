// Javascript Document
(()=> {
  return {
    data() {
      return {
        project: null,
        root: appui.plugins['appui-database'] + '/',
        link: ''
      }
    },
    computed: {
      isRealVirtual(){
        return this.source.is_real && this.source.is_virtual;
      },
      isOnlyVirtual(){
        return !this.source.is_real && this.source.is_virtual;
      }
    },
    mounted() {
      this.project = this.closest('appui-project-ui');
      this.link = (this.project ? this.project.root + 'database/' : this.root + 'tabs/' + this.source.engine + '/') +
        this.source.id_host + '/' + this.source.database + '/' + this.source.name + '/home';
    }
  }
})();
