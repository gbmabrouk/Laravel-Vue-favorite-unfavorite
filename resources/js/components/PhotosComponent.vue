<template>
    <div class="container" v-if="!guest">
        <div class="row">
        <div class="col-md-4" v-for="photo in displayedPhotos" :key="photo.id">
            <div class="card mb-4 box-shadow post-cards">
            <div class="card-body">
                <!-- <h5 class="capitalize">{{ photo.title }}</h5> -->
                <div class="center-bloc">
                    <img :src="photo.thumbnailUrl" alt="">
                    <img :src=imgfav alt="" class="fav-icon" v-if="findFav(photo.id)" @click="unfavorite(photo.id)">
                    <img :src=imgunfav alt="" class="fav-icon" v-else @click="favorite(photo.id, photo.thumbnailUrl)">
                </div>
            </div>
            </div>
        </div>
        </div>

        <div class="btn-group col-md-2 offset-md-5">
            <button type="button" class="btn btn-sm btn-outline-secondary btn-width" v-if="page != 1" @click="page--"> Previous </button>
            <button type="button" @click="page++" v-if="Photos.length >= 10 && this.page <= (this.Photos.length / this.perPage)" class="btn btn-sm btn-outline-secondary btn-width"> Next </button>
        </div>
    </div>
    <div class="container" v-else>
        <div class="row">
        <div class="col-md-4" v-for="photo in displayedPhotos" :key="photo.id">
            <div class="card mb-4 box-shadow post-cards">
            <div class="card-body">
                <div class="center-bloc">
                    <img :src="photo.imgUrl" alt="">
                </div>
            </div>
            </div>
        </div>
        </div>

        <div class="btn-group col-md-2 offset-md-5">
            <button type="button" class="btn btn-sm btn-outline-secondary btn-width" v-if="page != 1" @click="page--"> Previous </button>
            <button type="button" @click="page++" v-if="isFavorited.length >= 10 && this.page <= (this.isFavorited.length / this.perPage)" class="btn btn-sm btn-outline-secondary btn-width"> Next </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['guest'],
        data(){
            return{
                Photos: [],
                page: 1,
                perPage: 9,
                isFavorited: [],
                imgfav: '/images/favorite.png',
                imgunfav: '/images/unfavorite.png'
            }
        },
        created(){
            this.fetchPhotos(this.guest);
            if(!this.guest){
                this.isFavorite();
            }
        },
        methods: {
            favorite(imgId, url){
                axios.post('setFavorite/'+imgId+'/'+url)
                    .then(response => this.isFavorited.push(response.data.data))
                    .catch(response => console.log(response));
            },
            unfavorite(imgId){
                axios.delete('setUnfavorite/'+imgId) 
                    .then(response => {
                        let index = this.isFavorited.findIndex(function(o){
                            return o.image_id == imgId;
                        })
                        if (index !== -1) this.isFavorited.splice(index, 1);
                        })
                    .catch(response => console.log(response));
            },
            fetchPhotos(guest){
                axios.get('http://jsonplaceholder.typicode.com/photos')
                .then(response => {
                    this.Photos = response.data;
                })
                .catch(response => {
                    console.log(response);
                });
                if (guest) {
                    axios.get('getFavorites')
                    .then(response => {
                        //Now let's remove the redundancy
                        let favPhotos = response.data.data;
                        let removeRedund = [];
                        $.each(favPhotos, function(i, el){
                            if(!removeRedund.find(x => x.image_id == el.image_id)) removeRedund.push(el);
                        });
                        this.isFavorited = removeRedund;
                    })
                    .catch(response => {
                        console.log(response);
                    });
                }
            },
            isFavorite() {
                //get array of favorited images for the current auth user
                axios.get('seekFavorite')
                    .then(response => {
                        if (response) {
                            this.isFavorited = response.data.data;
                        }else{
                            console.log('failed to get the list from db!');
                        }
                    })
                    .catch(response => console.log(response));
            },
            findFav(id){
                if(!this.isFavorited.find(x => x.image_id == id)){
                    return false;
                }
                return true;
            },
            paginate (images) {
                let page = this.page;
                let perPage = this.perPage;
                let from = (page * perPage) - perPage;
                let to = (page * perPage);
                return  images.slice(from, to);
            }
        },
        computed: {
            displayedPhotos () {
                if(this.guest){
                    return this.paginate(this.isFavorited);
                }
                return this.paginate(this.Photos);
            }
        }
    }
</script>
