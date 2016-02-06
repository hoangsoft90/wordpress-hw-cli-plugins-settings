# install & active the list of plugins
# example: ./hw-config-plugin.sh "/path/to/wordpress"
path=$1

ln -s /home/$USER/public_html ~/pathABC
cd ~/pathABC

#
# first to active my plugin for import/export
wp plugin install https://dl.dropboxusercontent.com/u/16994321/hoangweb/wp-plugins/hw-cli-plugins-settings.zip --activate --url="$path"
#wp plugin delete hw-cli-plugins-settings
wp plugin activate hw-cli-plugins-settings  --url="$path"

#yoast seo
wp plugin install wordpress-seo --activate  --url="$path"
wp hw-wpseo import_settings  --url="$path"

# cache
wp plugin install w3-total-cache --activate  --url="$path"
wp hw-w3cache import_settings  --url="$path"

wp plugin install wordfence --activate  --url="$path"
wp plugin install captcha --activate  --url="$path"
wp plugin install google-authenticator --activate  --url="$path"

#security
wp plugin install wp-security-scan --activate

# finally, deactivate & delete my plugin
wp plugin deactivate hw-cli-plugins-settings
wp plugin delete hw-cli-plugins-settings

echo "===================================================\n"
echo "Hoan tat !"
echo "===================================================\n"