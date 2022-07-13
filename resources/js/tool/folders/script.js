import Cropper from 'cropperjs';
import Toasted from 'toastedjs'
let toasted = new Toasted({
  theme: 'nova',
  position: 'bottom-right',
  duration: 6000,
})
export default {
  props: {
    type: { type: String, default: 'folder' },
    label: { type: String, default: '' }
  },
  data() {
    return {
      title: {
        back: '',
        create: '',
        remove: '',
        folder: ''
      },
      className: ''
    }
  },
  methods: {
    action(type) {
      let parent = this.$parent.$parent;
      parent.filter.title = '';

      if ( 'folder' === type ) {
        //this.$set(parent.filter, 'folder', parent.filter.folder + this.label + '/');
        parent.filter['folder'] = parent.filter.folder + this.label + '/';
        parent.clearData();
        parent.get();
      } else if ( 'back' === type ) {
        let array = parent.filter.folder.split('/');
        array.pop();
        array.pop();
        //this.$set(parent.filter, 'folder', array.join('/') + '/');
        parent.filter['folder'] = array.join('/') + '/';
        parent.clearData();
        parent.get();
      } else if ( 'remove' === type ) {
        if ( !confirm(this.__('Delete this folder?')) ) return;

        Nova.request().post('/nova-vendor/nova-media-library/folder/del', { folder: parent.filter.folder }).then(r => {
          if ( r.data.folders )
            //this.$set(this.$parent.$parent.config, 'folders', r.data.folders);
            this.$parent.$parent.config['folders'] = r.data.folders;
          if ( r.data.message )
            toasted.show(r.data.message, { type: 'success' });
          this.action('back');
        }).catch(e => {
          window.nmlToastHook(e);
        });
      } else if ( 'create' === type ) {
        let folder = prompt(this.__('New folder name'));
        if ( !folder ) return;

        Nova.request().post('/nova-vendor/nova-media-library/folder/new', {
          base: parent.filter.folder,
          folder: folder
        }).then(r => {
          if ( r.data.folders )
            //this.$set(this.$parent.$parent.config, 'folders', r.data.folders);
            this.$parent.$parent.config['folders'] = r.data.folders;
          if ( r.data.message )
            toasted.show(r.data.message, { type: 'success' });
        }).catch(e => {
          window.nmlToastHook(e);
        });
      }
    }
  },
  created() {
    this.title.folder = this.label;
    this.className = 'folder' === this.type ? '' : '-'+this.type;
  }
  
}
