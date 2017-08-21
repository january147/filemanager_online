import os
import Image

Dir = os.listdir('./icons')
for file in Dir:
  im = Image.open('./icons/'+file)
  w,h = im.size;
  print w,h;
  im.save('./icons_m'+file,'png')
