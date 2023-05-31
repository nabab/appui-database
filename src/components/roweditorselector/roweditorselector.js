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
      select() {
        bbn.fn.log(arguments);
      }
    },
    mounted() {
      bbn.fn.post(appui.plugins['appui-database'] + '/list-project', {}, d => {
        bbn.fn.log('data:', d);
        this.project = d.project;
        this.currentPathId = d.project.path[d.project.path.length - 1].id;
        this.dataTree.id_project = d.project.id;
        this.ready = true;
      });
    },
    watch: {
      currentPathId(v) {
        this.dataTree.id_path = v;
        //this.getRef('tree').$forceUpdate();
        this.getRef('tree').updateData();
      }
    }
  };
})();