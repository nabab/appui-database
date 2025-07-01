// Javascript Document
(() => {
  return {
    props: ['source'],
    data() {
      const routerSource = [{
        fixed: true,
        url: 'home',
        icon: this.source.icon || 'nf nf-fa-list_alt',
        notext: true,
        label: bbn._('List'),
        load: true,
        bcolor: '#666',
        fcolor: '#FFF'
      }];

      if (this.source.hasConsole) {
        routerSource.push({
          icon: 'nf nf-fa-terminal',
          notext: true,
          url: 'console',
          label: bbn._('Console'),
          fixed: true,
          component: 'appui-database-console',
          options: {
            database: this.source.database,
          },
          bcolor: '#666',
          fcolor: '#FFF'
        });
      }

      return {
        routerSource,
        test: 1
      };
    },
    created() {
      if (this.source.isRoot) {
        appui.register('appui-databases', this);
      }
    },
    beforeDestroy() {
      if (this.source.isRoot) {
        appui.unregister('appui-databases');
      }
    }
  };
})();
