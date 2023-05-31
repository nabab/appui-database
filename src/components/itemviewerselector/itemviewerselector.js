// Javascript Document

(() => {
  return {
    data() {
      return {
        dataTree: {
          id_project: '',
          type: 'components',
          id_path: ''
        },
        root: appui.plugins['appui-newide'] + '/',
        ready: false,
        project: [],
        currentPathId: ''
      };
    },
    methods: {
      getPrefix() {
        for (let i in this.project.path) {
          if (i.id === this.currentPathId) {
            return i.code;
          }
        }
        return bbn.env.appPrefix;
      },
      trimUid(uid) {
        for (let i = uid.length - 1; uid[i] != '/'; i--) {
          uid = uid.slice(0, -1);
        }
        uid = uid.slice(0, -1);
        return uid;
      },
      getComponentFullName(uid) {
				let componentName = (this.trimUid(uid)).replaceAll('/', '-');
        return this.getPrefix() + componentName;
      },
      select(item) {
        const data = item.data;
        if (!data.is_vue) {
          return;
        }
        let table = this.closest('appui-database-table');
        table.save('viewer', this.getComponentFullName(data.uid), '');
        this.getPopup().close();
      }
    },
    mounted() {
      bbn.fn.post(appui.plugins['appui-database'] + '/list-project', {}, d => {
        this.project = d.project;
        this.currentPathId = d.project.path[d.project.path.length - 1].id;
        this.dataTree.id_project = d.project.id;
        this.ready = true;
      });
    },
    watch: {
      currentPathId(v) {
        this.dataTree.id_path = v;
        //this.getRef('tree').updateData();
      }
    }
  };
})();