// Javascript Document
(() => {
  return {
    props: ['source'],
    data(){
      const routerSource = [{
        fixed: true,
        url: 'home',
        icon: this.source?.icon,
        notext: true,
        title: bbn._('List'),
        component: "appui-database-host",
        bcolor: '#666',
        fcolor: '#FFF'
      }, {
        fixed: true,
        url: 'console',
        icon: "nf nf-md-console",
        notext: true,
        title: bbn._('Console'),
        component: "appui-database-console",
        bcolor: '#666',
        fcolor: '#FFF'
      }];
      bbn.fn.log("ciao", this.source, routerSource);
      return {
        routerSource,
        test: 1
      };
    },
    created() {
      let dbInfo;
      try {
        dbInfo = appui.getRegistered("database-info");
      } catch (e) {
				bbn.fn.post(appui.plugins["appui-database"], (d) => {
          if (d) {
            dbInfo = d;
          }
        });
      }
    }
  };
})();
