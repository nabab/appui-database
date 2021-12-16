(() => {
  return {
    methods: {
      setPollerProp(obj){
        if (bbn.fn.isObject(obj)) {
          if (!('appui-database' in appui.pollerObject)) {
            appui.$set(appui.pollerObject, 'appui-database', {});
          }
          if (!('sync' in appui.pollerObject['appui-database'])) {
            appui.$set(appui.pollerObject['appui-database'], 'sync', {});
          }
          appui.$set(
            appui.pollerObject['appui-database'],
            'sync',
            bbn.fn.extend(true, {}, appui.pollerObject['appui-database'].sync, obj)
          );
          appui.poll();
        }
      },
      startConflictsPoller(){
        if (!!this.source.conflictsFilesHash) {
          this.setPollerProp({
            conflictsHash: this.source.conflictsFilesHash
          });
        }
      },
      startStructuresPoller(){
        if (!!this.source.structuresFilesHash) {
          this.setPollerProp({
            structuresHash: this.source.structuresFilesHash
          });
        }
      }
    },
    created(){
      appui.register('appui-database-sync', this);
      appui.$on('appui-database', (type, data) => {
        if (type === 'message') {
          if ('sync' in data) {
            if ('conflictsFiles' in data.sync) {
              try {
                let comp = appui.getRegistered('appui-database-sync-conflicts');
                if (bbn.fn.isVue(comp) && bbn.fn.isFunction(comp.receive)) {
                  comp.receive(data.sync.conflictsFiles);
                }
              }
              catch (e) {
                bbn.fn.log(e);
              }
            }
            if ('structuresFiles' in data.sync) {
              try {
                let comp = appui.getRegistered('appui-database-sync-structures');
                if (bbn.fn.isVue(comp) && bbn.fn.isFunction(comp.receive)) {
                  comp.receive(data.sync.structuresFiles);
                }
              }
              catch (e) {
                bbn.fn.log(e);
              }
            }
          }
        }
      });
    },
    beforeDestroy(){
      appui.unregister('appui-database-sync');
      appui.$off('appui-database');
    }
  }
})();
