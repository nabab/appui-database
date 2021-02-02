(() => {
  return {
    created(){
      appui.$on('appui-database', (type, data) => {
        if (type === 'message') {
          if ('sync' in data) {
            let conflictsComp = this.find('appui-database-sync-conflicts');
            if (bbn.fn.isVue(conflictsComp) && bbn.fn.isFunction(conflictsComp.receive)) {
              conflictsComp.receive(data.sync);
            }
          }
        }
      });
      if (!('appui-database' in appui.pollerObject)) {
        appui.$set(appui.pollerObject, 'appui-database', {
          sync: {
            conflictsHash: this.source.filesHash
          }
        });
      }
      else {
        appui.$set(appui.pollerObject['appui-database'], 'sync', {
          conflictsHash: this.source.filessHash
        });
      }
      appui.poll();
    },
    beforeDestroy(){
      appui.$off('appui-database');
    }
  }
})();
