// share_card/pages/product/index.js
import http from '../../util/request.js';
import alert from '../../util/alert.js';
import { toast } from '../../util/alert.js';
import router from '../../util/router.js';
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    catalogs:[],
    attachurl:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.load();
  },

  load() {
    let that = this;
    const attachurl = wx.getStorageSync('attachurl');
    http.get('catalogs')
      .then((res) => {
        that.setData({ catalogs: res, attachurl: attachurl });
      }, err => {
        console.log(err);
        alert('获取分类失败')
      }).then(() => {
        console.log('router');
        router.index();
      });
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})