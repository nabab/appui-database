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
        if (!!this.source.filesHash) {
          this.setPollerProp({
            conflictsHash: this.source.filesHash
          });
        }
      }
    },
    created(){
      appui.register('appui-database-sync', this);
      appui.$on('appui-database', (type, data) => {
        if (type === 'message') {
          if ('sync' in data) {
            try {
              let conflictsComp = appui.getRegistered('appui-database-sync-conflicts');
              if (bbn.fn.isVue(conflictsComp) && bbn.fn.isFunction(conflictsComp.receive)) {
                conflictsComp.receive(data.sync);
              }
            }
            catch (e) {
              bbn.fn.log(e);
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
